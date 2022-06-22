<?php
// cand dau index.php -> .../tw/index.php?load=Home/show/querry

    $cntrl = 'Home';// incerc sa contruies controllerul
    $cmd = 'show'; // functia pe care o voi apela
    $querry = 1;
    if(isset($_GET['load'])){
        $parametrii = array();

        $val = $GET['load'];
        $parametrii = explode("/",$val);
        // parametrii 0 => numele controllerului care trebuie folosit 
        $cntrl = $parametrii[0];
        if(!empty($parametrii[1]) && isset($parametrii[1]) )
        {
            //are valoare
            // paramtetru 1 =>  functia  in sine care trebuie apelata
            $cmd = $parametrii[1];
        }
        if(!empty($parametrii[2]) && isset($parametrii[2]) )
        {
            //are valoare
            // paramtetru 1 =>  functia  in sine care trebuie apelata
            $querry = $parametrii[2];
        }
    }

    $cntrl .= 'Controller'; // append Controller to the page i wanted 
    
        // echo $cntrl;

    $actualControllerClass = new $cntrl();
    if(method_exists($actualControllerClass, $cmd)){
        // echo 'heere';
        $actualControllerClass->$cmd($querry);

    }
    else{
        return new Exception(" Problem with laoding the page (boot.php)");
    }
    

?>