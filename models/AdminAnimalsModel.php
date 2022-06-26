<?php 
    class AdminAnimalsModel extends Model{
        public function __construct()
        {
            // echo "should show";
            parent::__construct();
        
        }

        public function get_animal_field_value(){
            //ambele campuri sunt oblicatorii

            if(!(isset($_POST['animal_name']) && isset($_POST['field_name'])&&!empty($_POST['animal_name']) && !empty($_POST['field_name']))){
                return 'Ambele campuri trebuie sa fie completate';
                echo ' campuri necompletate';
            }
            
            $campuri=array("ID","DENUMIRE_POPULARA","DENUMIRE_STINTIFICA","MINI_DESCRIERE","ETIMOLOGIE","ORIGINE","CLASA","HABITAT","INVAZIVA","STARE_DE_CONSERVARE","REGIM_ALIMENTAR","DIETA","MOD_DE_INMULTIRE","REPRODUCERE","DEZVOLTARE","VIATA","ISTORIE","DUSMANI_NATURALI","NR_ACCESARI","NR_SALVARI","NR_DESCARCARI","CREAT_LA","ACTUALIZAT_LA");
            $animal = $_POST['animal_name'];
            // echo $animal;
            $camp = $_POST['field_name'];
            // echo "<br> $camp";
            $camp = strtoupper($camp);
            if(!in_array($camp,$campuri)){
                $string = 'Campul nu este scris corect <br> campurile disponibile sunt <br>' ;
                foreach ($campuri as  $value) {
                    $string.= ", $value ";
                }
                return $string;
            }
            $comandaSQL = "select $camp from animale where upper(denumire_populara) = upper( '$animal')";
            // echo "<br> $comandaSQL";
            $statment = Database::getConn()->prepare($comandaSQL);
            $statment->execute();
            $rezultat = $statment->fetchAll();
            // var_dump($rezultat);
            if(count($rezultat)!=0)
            {
                $valoare = (string) $rezultat[0][$camp];
                // var_dump($valoare);
                return array('field_value' => $valoare, 'type'=>'animal_info');
            }
            return null;
        }
        public function change_animal_field_value(){
            // toate cele 3 capuri trebuie sa fie setate
            if(!(isset($_POST['animal_name']) && isset($_POST['field_name'])&&!empty($_POST['animal_name']) && !empty($_POST['field_name']) && isset($_POST['field_value'])&& !empty($_POST['field_value']))){
                // echo 'Campuri necompletate';
                return 'Campuri necompletate';
                
            }

            $animal = $_POST['animal_name'];
            $animal = strtoupper( str_replace(' ','_',$animal));
            $fieldName = $_POST['field_name'];
            $fieldName = strtoupper( str_replace(' ','_',$fieldName));
            $valoareNoua = $_POST['field_value'];

            $campuri=array("ID","DENUMIRE_POPULARA","DENUMIRE_STINTIFICA","MINI_DESCRIERE","ETIMOLOGIE","ORIGINE","CLASA","HABITAT","INVAZIVA","STARE_DE_CONSERVARE","REGIM_ALIMENTAR","DIETA","MOD_DE_INMULTIRE","REPRODUCERE","DEZVOLTARE","VIATA","ISTORIE","DUSMANI_NATURALI","NR_ACCESARI","NR_SALVARI","NR_DESCARCARI","CREAT_LA","ACTUALIZAT_LA");
            if(!in_array($fieldName,$campuri)){
                // echo ' Nu exista acest camp';
                return 'Nu exista acest camp';
            }
            $comandaSQL = "update animale set $fieldName = '$valoareNoua'  where upper(DENUMIRE_POPULARA) = '$animal'";
            // echo $comandaSQL;
            $statement = Database::getConn() -> prepare($comandaSQL);
            $statement -> execute();
            $rezultat  = $statement -> fetchAll();
            // var_dump($rezultat);
            if(count($rezultat)!=0)
            {
                $valoare = (string) $rezultat[0][$fieldName];
                // var_dump($valoare);
                return array('field_value' => $valoare, 'type'=>'animal_info');
            }
            return null;
        }

        public function deleteAnimal(){
            // numele animalului trebuie sa fie setat
            if(!(isset($_POST['animal_name']) && !empty($_POST['animal_name']))){
                return 'Campuri necompletate';
            }
            $animal = $_POST['animal_name'];
            $comandaSQL = "delete from animale where upper(DENUMIRE_POPULARA) = upper('$animal')";
            $statement = Database::getConn()->prepare($comandaSQL);
            $statement -> execute();

            
        }

        

    }
?>