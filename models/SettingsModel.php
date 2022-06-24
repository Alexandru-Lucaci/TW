<?php 
    // session_start();
    class SettingsModel extends Model{
        public function __construct()
        {
            // echo "should show";
            parent::__construct();
        
        }
        public function getInformation(){
            if(isset($_SESSION['name'])&& !empty($_SESSION['name'])){
                //it's ok
                // echo 'ok';
                $user = htmlentities(($_SESSION['name']));
                // echo $_SESSION['name'];
                $comandaSql = 'Select * from utilizatori where nume_utilizator= (?)';

                $statement = Database::getConn()->prepare($comandaSql);
                $statement->bindParam(1,$user,PDO::PARAM_STR,100);
                $statement->execute();
                // associative array
                $output=$statement->fetchAll();
                
                // if(!empty($output)){
                return array('user_info' => $output[0]);
                // }
                // else
                // {
                //     // cam imposibil => n-am gasit
                //     return 'failed Something is wrong, i shouldn\'t be hereeeeee';
                // }

            }else{
                /// caz in care nu voi fi niciodata - sa fiu considerat logat dar sa nu stiu numele de utilizator
                return 'failed Something is wrong, i shouldn\'t be here';

            }
        }
        public function changeInfo(){
            if(isset($_SESSION['name'])&& !empty($_SESSION['name']))
                // echo '<br> i got heeere <br>yess';
                $changeValue = null;
                $fieldname = null;
                if(isset($_POST['field_value']))
                {
                    // echo '<br> valoarea este setata';
                    if(!empty($_POST['field_value']))
                    {
                        $changeValue = $_POST['field_value'];
                        
                        $fieldname = $_POST['field_name'];
                        
                        if($fieldname=='email')
                        {
                            if(filter_var($changeValue,FILTER_VALIDATE_EMAIL))
                            {
                                $ussname = $_SESSION['name'];
                                $result = null;
                                $comandaSql = 'call schimbare_camp_utilizator(?,?,?,?)' ;
                                $statement = Database::getConn()->prepare($comandaSql);
                                $statement->bindParam(1,$ussname,PDO::PARAM_STR,100);
                                $statement->bindParam(2,$fieldname,PDO::PARAM_STR,100);
                                $statement->bindParam(3,$changeValue,PDO::PARAM_STR,100);
                                $statement->bindParam(4,$result,PDO::PARAM_STR|PDO::PARAM_INPUT_OUTPUT,100);
                                // echo $statement;
                                $statement->execute();
                                if($result != 'OK'){
                                    echo $result;
                                    return $result;
    
                                }

                                return $this->getInformation();
                            }
                            else 
                            {
                                $result = 'Not a good email';
                                echo $result;
                                return $result;
                            }
                        }
                        else {
                            $ussname = $_SESSION['name'];
                            $result = null;
                            $comandaSql = 'call schimbare_camp_utilizator(?,?,?,?)' ;
                            $statement = Database::getConn()->prepare($comandaSql);
                            $statement->bindParam(1,$ussname,PDO::PARAM_STR,100);
                            $statement->bindParam(2,$fieldname,PDO::PARAM_STR,100);
                            $statement->bindParam(3,$changeValue,PDO::PARAM_STR,100);
                            $statement->bindParam(4,$result,PDO::PARAM_STR|PDO::PARAM_INPUT_OUTPUT,100);
                            // echo $statement;
                            $statement->execute();
                            if($result != 'OK'){
                                echo $result;
                                return $result;

                            }
                            if($fieldname == 'nume_utilizator'){
                                $_SESSION['name'] = $changeValue;
                                $_SESSION['login']= 1;
                            }
                            return $this->getInformation();
                            // if($)
                        }
                    }
                    else
                    {
                        return $this->getInformation();
                    }

                }
                else{
                    //Cam niciodata nu voi ajunge aici
                    return $this->getInformation();
                }
        }

        public function delete(){
            if(isset($_SESSION['name'])&& !empty($_SESSION['name'])){
                    $usname = $_SESSION['name'];
                    $result=null;
                    $comandaSql = 'call sterge_utilizator(?,?)';
                    $statement= Database::getConn()->prepare($comandaSql);
                    $statement->bindParam(1,$usname,PDO::PARAM_STR,100);
                    $statement->bindParam(2,$result,PDO::PARAM_STR|PDO::PARAM_INPUT_OUTPUT,100);
                    $statement->execute(); 
                    echo 'here i am';
                    echo $result;
                    if($result == 'OK'){
                        $_SESSION['name']=null;
                        $_SESSION['login'] =0;
                        return $result;
                    }
                    else
                        {
                            echo $result;
                            return $result;
                        }
            }
            else
            {
                // i shouldn't be here, cam imposibil sa ajung aici

                return 'Nu sunt conectat';

                
            }
        }
    
    }
?>