<?php 
    class SavingsModel extends Model{
        public function __construct()
        {
            // echo "should show";
            parent::__construct();
        
        }
        public function get_saved_animals(){
            if(isset($_SESSION['login'])&& !empty($_SESSION['login'])){
                // echo 'yess';
                $name = $_SESSION['name']; 
                // echo $name;
                $idName = AnimalsModel::getPersonId($name);
                // echo $idName;
                $comandaSQL = "select denumire_populara, denumire_stintifica, mini_descriere from animale 
                join salvari on id_utilizator = ". $idName. " and id = id_animal";
                $statement=Database::getConn()->prepare($comandaSQL);
                // $statement->bindParam(1, $name, PDO::PARAM_STR, 100);
                $statement -> execute();
                $rezultat = $statement->fetchAll();
                $_SESSION['saved_animals'] = $rezultat;
                $_SESSION['savings_page_number'] =1;
                // var_dump($rezultat);
                return $rezultat;

            }

        }
        public function change_savings_page(){
            $nrPag = $_SESSION[
                'savings_page_number'
            ];
            // echo $nrPag;
            $value = $_POST['change_value']; // 1 sau -1
            // echo $value;
            $animale = count($_SESSION['saved_animals']);
            if($value == 1){
                $animalePagina = $_POST[
                    'results_per_page'
                ];
                // echo $animalePagina;
                if(!($nrPag*$animalePagina<$animale)){
                    echo 'heeeere x2';
                    return 'este ultima pagina';
                }
                $nrPag++;
                // echo ' heeere x3';
            }
            if($value == -1){
                if($nrPag == 1) return 'este prima pagina';
                $nrPag--;
            }
            // echo 'heeere';
            $_SESSION['savings_page_number'] = $nrPag;
            return 'OK';
        }
    
    }
?>