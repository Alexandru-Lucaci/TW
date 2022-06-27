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
                    $valori = array();
                    $valori['DENUMIRE_POPULARA'] = $_POST['DENUMIRE_POPULARA'];
                    $valori['DENUMIRE_STINTIFICA'] = $_POST['DENUMIRE_STINTIFICA'];
                    $
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