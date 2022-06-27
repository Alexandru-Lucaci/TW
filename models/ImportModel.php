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
                    // echo $comandaSQL;
                    $statement = Database::getConn()->prepare($comandaSQL);

                    $statement -> execute();
                    return 'ok';
                }
                else
                {
                    // echo 'prin fisier';
                    // inserez prin fisier 
                    // echo file_get_contents($_FILES['file']);
                    // var_dump($_FILES['file']);
                    // echo $_FILES['file'];
                    // echo "Filename: " . $_FILES['file']['name']."<br>";
                    // echo "Type : " . $_FILES['file']['type'] ."<br>";
                    $tip = $_FILES['file']['type'];
                    $tip =  strtoupper(substr($tip,-3)) ;
                    // echo $tip . '<br>';
                    // echo "Size : " . $_FILES['file']['size'] ."<br>";
                    // echo "Temp name: " . $_FILES['file']['tmp_name'] ."<br>";
                    // echo "Error : " . $_FILES['file']['error'] . "<br>";
                    if ($tip =='SON'){
                        $comandaSQL = "select MAX(ID)+1  from animale";
                        $statement = Database::getConn()->prepare($comandaSQL);
                        $statement -> execute();
                        $result = $statement->fetchAll();
                        $valori = array();
                        // var_dump($result);
                       
                        // echo file_get_contents( $_FILES['file']['tmp_name']);
                        $valori = json_decode(file_get_contents( $_FILES['file']['tmp_name']));
                        // var_dump($valori);
                        // $valori['ID'] = (string) $result[0]["MAX(ID)+1"];
                        $denumiri ='( ID , ';
                        $valoriNoi =" ( " . $result[0]["MAX(ID)+1"] . ' , ';
                        $i=0;
                        
                        foreach ($valori as $key => $value) {
                            // if($i== (count($valori)-1))
                            // {
                            //     $denumiri .= $key .' ) ';
                            //     $valoriNoi .= '\''. $value . '\' ) ';
                            // }
                            // else{
                                $denumiri .= $key .' , ';
                                $valoriNoi .= '\'' . $value . '\' , ';
                            }
                            // $i++;
    
                        // }

                        $denumiri[strlen($denumiri)-2] =')';
                        // echo '<br><br><br>';
                        // var_dump($denumiri);
                        // echo '<br><br><br>';
                        $valoriNoi[strlen($valoriNoi)-2] =')';
                        // var_dump(($valoriNoi));
    
    
                        
    
    
                        $comandaSQL = "INSERT INTO animale $denumiri VALUES $valoriNoi";
                        // echo $comandaSQL;
                        $statement = Database::getConn()->prepare($comandaSQL);
    
                        $statement -> execute();
                        return 'ok';
                    }
                    else{
                        if($tip == 'XML'){
                            // $handle = fopen($_FILES['file']['tmp_name'],'r');
                          
                            // $data = fread($handle,filesize($_FILES['file']['tmp_name']));

                            

                            // $fileNou = file($_FILES['file']['tmp_name'],FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                            // var_dump($fileNou);
                            // echo "<br><br> de aici".$fileNou['0'];
                            // echo "<br><br> $data<br><br>";

                            /// here i have an probllem
                            $valori= simplexml_load_file($_FILES['file']['tmp_name']);
                            $comandaSQL = "select MAX(ID)+1  from animale";
                            $statement = Database::getConn()->prepare($comandaSQL);
                            $statement -> execute();
                            $result = $statement->fetchAll();
                            // var_dump( $file);
                            $denumiri ='( ID , ';
                            $valoriNoi =" ( " . $result[0]["MAX(ID)+1"] . ' , ';
                            $i=0;
                            
                            foreach ($valori as $key => $value) {
                                    $denumiri .= $key .' , ';
                                    $valoriNoi .= '\'' . $value . '\' , ';
                                }

                                $denumiri[strlen($denumiri)-2] =')';
                                $valoriNoi[strlen($valoriNoi)-2] =')';
                                $comandaSQL = "INSERT INTO animale $denumiri VALUES $valoriNoi";
                                $statement = Database::getConn()->prepare($comandaSQL);
            
                                $statement -> execute();
                                return 'ok';
                        }else{
                            return 'Something is not good';
                        }
                    }
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