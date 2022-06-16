<?php

    if(is_array($data)){

        echo "<pre>";
        print_r($data);
        echo "</pre>";


        //update session variables in case of request
        $toBeUpdated=array('user_info','animal_info','list_users','list_animals','users_saved_animals','animal_saved_by_users');
        if(isset($data['type'])&&in_array($data['type'],$toBeUpdated)){
            
            //take type and unset it
            $type=$data['type'];
            unset($data['type']);
            
            $_SESSION['admin'][$type]=$data;

            echo "<p>Updated $type</p>";
        }
        else{
            echo "<p>Nothing to update</p>";
        }
    }
    else{
        echo "<h3>$data</h3>";
    }

?>