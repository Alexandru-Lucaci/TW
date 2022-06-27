<?php 
    class ImportModel extends Model{
        public function __construct()
        {
            // echo "should show"; 
            parent::__construct();
        
        }

        public function insert_animal_info(){
            // primele trei campuri sunt obligatorii
            // input_option
            // DENUMIRE_POPULARA
            // DENUMIRE_STINTIFICA
            // MINI_DESCRIERE
            if(isset($_POST['input_option']) && !empty($_POST['input_option'])) {

                
                $tipInsert = $_POST['input_option'];
                if($tipInsert == 'direct'){
                    //inserez prin input direct 
                    if(!( isset($_POST['DENUMIRE_POPULARA']) && isset($_POST['DENUMIRE_STINTIFICA'])&& isset($_POST['MINI_DESCRIERE']) && 
                         !empty($_POST['DENUMIRE_POPULARA']) && !empty($_POST['DENUMIRE_STINTIFICA'])&& !empty($_POST['MINI_DESCRIERE']))){
        
                        echo 'variabile care nu au fost completate';
                        return 'variabile care nu au fost completate';
                    }
                    $comandaSQL = "select MAX(ID)+1  from animale";
                    $statement = Database::getConn()->prepare($comandaSQL);
                    $statement -> execute();
                    $result = $statement->fetchAll();
                    $valori = array();
                    // var_dump($result);
                    $valori['ID'] = $result[0]["MAX(ID)+1"];
                    $valori['DENUMIRE_POPULARA'] = $_POST['DENUMIRE_POPULARA'];
                    $valori['DENUMIRE_STINTIFICA'] = $_POST['DENUMIRE_STINTIFICA'];
                    $valori['MINI_DESCRIERE'] = $_POST['MINI_DESCRIERE'];

                    // var_dump($valori);
                    // ETIMOLOGIE
                    // ORIGINE
                    //CLASA
                    // HABITAT
                    // STARE_DE_CONSERVARE
                    // INVAZIVA
                    // REGIM_ALIMENTAR
                    // DIETA
                    // MOD_DE_INMULTIRE
                    // REPRODUCERE
                    // DEZVOLTARE
                    // VIATA
                    // ISTORIE
                    // DUSMANI_NATURALI

                    if(isset($_POST['ETIMOLOGIE']) && !empty($_POST['ETIMOLOGIE'])){
                        $valori['ETIMOLOGIE'] = $_POST['ETIMOLOGIE'];

                    }
                    if(isset($_POST['ORIGINE']) && !empty($_POST['ORIGINE'])){
                        $valori['ORIGINE'] = $_POST['ORIGINE'];

                    }
                    if(isset($_POST['CLASA']) && !empty($_POST['CLASA'])){
                        $valori['CLASA'] = $_POST['CLASA'];

                    }
                    if(isset($_POST['HABITAT']) && !empty($_POST['HABITAT'])){
                        $valori['HABITAT'] = $_POST['HABITAT'];

                    }
                    if(isset($_POST['STARE_DE_CONSERVARE']) && !empty($_POST['STARE_DE_CONSERVARE'])){
                        $valori['STARE_DE_CONSERVARE'] = $_POST['STARE_DE_CONSERVARE'];

                    }
                    if(isset($_POST['REGIM_ALIMENTAR']) && !empty($_POST['REGIM_ALIMENTAR'])){
                        $valori['REGIM_ALIMENTAR'] = $_POST['REGIM_ALIMENTAR'];

                    }
                    if(isset($_POST['DIETA']) && !empty($_POST['DIETA'])){
                        $valori['DIETA'] = $_POST['DIETA'];

                    }
                    if(isset($_POST['MOD_DE_INMULTIRE']) && !empty($_POST['MOD_DE_INMULTIRE'])){
                        $valori['MOD_DE_INMULTIRE'] = $_POST['MOD_DE_INMULTIRE'];

                    } 
                    if(isset($_POST['REPRODUCERE']) && !empty($_POST['REPRODUCERE'])){
                        $valori['REPRODUCERE'] = $_POST['REPRODUCERE'];

                    }
                    if(isset($_POST['DEZVOLTARE']) && !empty($_POST['DEZVOLTARE'])){
                        $valori['DEZVOLTARE'] = $_POST['DEZVOLTARE'];

                    }
                    if(isset($_POST['VIATA']) && !empty($_POST['VIATA'])){
                        $valori['VIATA'] = $_POST['VIATA'];

                    }
                    if(isset($_POST['ISTORIE']) && !empty($_POST['ISTORIE'])){
                        $valori['ISTORIE'] = $_POST['ISTORIE'];

                    }
                    if(isset($_POST['DUSMANI_NATURALI']) && !empty($_POST['DUSMANI_NATURALI'])){
                        $valori['DUSMANI_NATURALI'] = $_POST['DUSMANI_NATURALI'];

                    }

                    // var_dump($valori);
                    $denumiri ='( ';
                    $valoriNoi =' ( ';
                    $i=0;

                    foreach ($valori as $key => $value) {
                        if($i== (count($valori)-1))
                        {
                            $denumiri .= $key .' ) ';
                            $valoriNoi .= '\''. $value . '\' ) ';
                        }
                        else{
                            $denumiri .= $key .' , ';
                            $valoriNoi .= '\'' . $value . '\' , ';
                        }
                        $i++;

                    }
                    // echo '<br><br><br>';
                    // var_dump($denumiri);
                    // echo '<br><br><br>';
                    // var_dump(($valoriNoi));


                    


                    $comandaSQL = "INSERT INTO animale $denumiri VALUES $valoriNoi";
                    echo $comandaSQL;
                    $statement = Database::getConn()->prepare($comandaSQL);

                    $statement -> execute();
                    return 'ok';
                }
                else
                {
                    echo 'prin fisier';
                    // inserez prin fisier 
                }

            }
            else{
                // cam imposibil
                echo ' ceva nu e ok';
                return 'ceva nu e ok input_option nu e sestat';
            }

        }
    
    }
?>