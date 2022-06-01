<?php

class AnimalsModel extends Model{

    function __construct(){
        parent::__construct();
    }

            /**
     * Helper function that takes a string of the format:
     * key1=value1,key2=value2,..#key1=value1,key2=value2,...
     * and returns an array of arrays containing the information
     */
    private static function get_array($text,$lineSeparator,$valueSeparator,$equalSeparator){
        $result=array();
        
        $lines=explode($lineSeparator,$text);
        foreach($lines as $line){

            $associative_array=array();

            $fields=explode($valueSeparator,$line);

            foreach($fields as $field){
                
                $set=explode($equalSeparator,$field);

                $key=$set[0];
                $value=$set[1];

                $associative_array[$key]=$value;
            }

            array_push($result,$associative_array);
        }

        return $result;
    }


    /**
     * Function that ,given a string with the values for the criterias and,using a helper function,returns/gives the values as an associative array
     * Input string format:criteriu1,valoare1,valoare2,...#crieteriu2,valoare1,valoare2,...
     */
    public function multicriterial_search(){
        
        //TODO
        //1.restrict the size of the query string ,maybe not bigger than 1000
        //2.since such a query might return more than 4000 characters(the limit for varchar2) we should use a large object 
        //to return the characters(in plsql)
        //3.make the result gotten from the procedure to be given as an associative array(would be nice to :) )


        $result='';

        if(isset($_POST['query'])&&!empty($_POST['query'])){
            $searchCriterias=$_POST['query'];

            $response=null;

            $sql="call cautare_multicriteriala(?,?,?)";

            $statement=Database::getConnection()->prepare($sql);

            $statement->bindParam(1,$searchCriterias,PDO::PARAM_STR,1000);
            $statement->bindParam(2,$result,PDO::PARAM_STR|PDO::PARAM_INPUT_OUTPUT,100000);
            $statement->bindParam(3,$response,PDO::PARAM_STR|PDO::PARAM_INPUT_OUTPUT,100);

            $statement->execute();

            if(is_null($response)){
                $result="Unexpected  error after sql statement";
            }
            else if($response!="OK"){
                $result=$response;
            }
            else{
                $result=$this::get_array($result,'#',',','=');
            }                
        }
        else{
            $result="Value for query is not set or empty";
        }

        return $result;
    }

    public function get_animal_information(){
        $result=null;

        if(isset($_POST['animal_name'])&&!empty($_POST['animal_name'])){
            $animalName=$_POST['animal_name'];
            
            $sql="select * from animale where lower(denumire_populara)=:name";

            $statement=Database::getConnection()->prepare($sql);

            $statement->execute([
                'name'=>$animalName
            ]);

            $result=$statement->fetchAll();
        }
        else{
            $result="Value for animal name not set or empty";
        }

        return $result;
    }    
}

?>