<?php 
    class AnimalsModel extends Model{
        private static function set_from_criterias($criterias){
            foreach ($criterias as $criteria )  {
                unset($_SESSION[$criteria]);
                if(isset($_POST[$criteria])&&!empty($_POST[$criteria])){
                    $_SESSION[$criteria] = $_POST[$criteria];
                }
            }
        }
        private static function compute_clauses(&$whereClause,&$whereColumns,&$orderByClause,&$orderByColumn){
            $nrTerms        = 0;
            $whereClause    = '';

            foreach ($whereColumns as $column ) {
                if(isset($_POST[$column])&&!empty($_POST[$column]))
                {
                    if($nrTerms>0){
                        $whereClause .= ' and ';
                    }
                    
                    $whereClause .= ' ( ';
                    $position = 0;
                    $values = $_POST[$column];
                    foreach($values as $value){
                        if($position!=0){
                            $whereClause .=' or ';

                        }
                        $whereClause .= 'lower(' . $column . ')=\'' . strtolower($value) . '\'';
                        $position ++;
                        
                    }
                    $whereClause .= " ) ";
                    $nrTerms++;

                }
            }

            $orderByClause ='';
            if(isset($_POST[$orderByColumn]) && !empty($_POST[$orderByColumn])){
                $position =0;
                $values = $_POST[$orderByColumn];
                foreach ($values as $value ) {
                    if($position!=0){
                        $orderByClause .= ' , ';
                    }
                    $orderByClause .= strtolower($value).' asc ';
                    $position++;
                }
            }

        }

        private static function set_search_results($results){
            $_SESSION['search_results'] = $results;
            $_SESSION['page_number']=1;
        }
        public function __construct()
        {
            // echo "should show";
            parent::__construct();
        
        }
        public function animal_saved($name, $animal){
            if(empty($name) || empty($animal)){
                return 'Problema, ambele campuri trebuie sa fie instantiate';
            }
           
            $comandaSQL = "select id from utilizatori where nume_utilizator = trim( ? )";
            $statement = Database::getConn()->prepare($comandaSQL);
            $statement->bindParam(1, $name, PDO::PARAM_STR,100);
            $statement->execute();
            $rezultat = $statement->fetchAll(); // ar trebui sa am doar o singura valoare 
            if(empty($rezultat))
            {
                return 'Ar trebui sa  exista un cont cu numele '. $name;
            }
            $personId = $rezultat[0]['ID'];
            // echo $rezultat[0]['ID'];
            $comandaSQL = "select id from animale where lower( denumire_populara ) = lower( ? )";
            $statement = Database::getConn()->prepare($comandaSQL);
            $statement->bindParam(1, $animal, PDO::PARAM_STR,100);
            $statement->execute();
            $rezultat = $statement->fetchAll(); // ar trebui sa am doar o singura valoare 
            if(empty($rezultat))
            {
                return 'Ar trebui sa  exista un animal cu numele '. $animal;
            }
            $animalId = $rezultat[0]['ID'];


            // verific daca exista in tabelul cu salvari 

            $comandaSQL = ' select count(*) from salvari where id_animal = (?) and id_utilizator = (?)';
            $statement = Database::getConn()->prepare($comandaSQL);
            $statement->bindParam(1, $personId, PDO::PARAM_STR,100);
            $statement->bindParam(1, $animalId, PDO::PARAM_STR,100);
            $statement->execute();
            $rezultat = $statement->fetchAll(); // ar trebui sa am doar o singura valoare 
            if($rezultat[0]['COUNT(*)']==0)
            {
                return false;
            }
            else
            {
                echo  $rezultat[0]['COUNT(*)'];
                return true;
            }
            


            
        }
        public function save_animals(){
            if(isset($_SESSION['login']) && $_SESSION['login'] ==1)
            {
                // sunt logat
                $ussname = $_SESSION['name'];
                if(isset($_POST['animal_names']) && !empty($_POST['animal_names'])){
                    $anima = $_POST['animal_names'];
                    $result =null;
                    $comandaSQL = "call salvare_animal(?,?,?,?)";


                    $statement=Database::getConn()->prepare($comandaSQL);
                    $statement->bindParam(1,$ussname,PDO::PARAM_STR,100);
                    $statement->bindParam(2,$anima,PDO::PARAM_STR,100);
                    $statement->bindParam(3,' , ',PDO::PARAM_STR,3);
                    $statement->bindParam(4,$result,PDO::PARAM_STR|PDO::PARAM_INPUT_OUTPUT,100);
                    
                    $statement->execute();
                    return $result;
                    

                }else{
                    return 'nimic';
                }

            }
            else
            {
                // nu sunt logat
                return 'Nu ar trebui sa pot apasa pe butonul asta';
            }
        }
        public function multicriterial_search(){
            $orderBy="ordonare";
            $columns = array("origine", "clasa", "habitat", "invaziva", "stare_de_conservare", "regim_alimentar", "mod_de_inmultire");
        
        // filterul meu poate face search pe doua ramuri where(unde) si (ordonare dupa)
        $whereClause='';
        $orderByClause ='';
        $this::compute_clauses($whereClause,$columns,$orderByClause, $orderBy);
        

        // setez sesion
        array_push($columns,$orderBy);
        $this::set_from_criterias($columns);
        //Comanda sql

        $comandaSQL ="select denumire_populara, denumire_stintifica,mini_descriere from animale ";
        if(strlen($whereClause)){
            $whereClause = ' where ' . $whereClause;
            $comandaSQL .= $whereClause;
        }
        if(strlen($orderByClause)>0){
            $orderByClause=' order by ' . $orderByClause;
            $comandaSQL .= $orderByClause;

        }      
        $statement = Database::getConn()->prepare($comandaSQL);
        $statement ->execute();
        $result = $statement->fetchAll();
        $_SESSION['search_results'] = $result;
        $_SESSION['page_number']=1;;
        }


    
    }
?>


