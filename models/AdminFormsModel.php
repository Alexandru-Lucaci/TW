<?php

class AdminFormsModel extends Model{

    function __construct(){
        parent::__construct();
    }

    /**
     * Obtine formulare din baza de date
     */
    public function get_forms(){
        
        $this->setQuery('select * from formulare_contact');

        $results=$this->getAll();

        $results['type']='list_forms';

        return $results;
    }

    /**
     * Schimba pozitia curenta a formularului adaugand o valoare,actualizand $_SESSION['admin']['form_position'] corespunzator
     * Valoarea cu care se chimba formularul este primita prin POST 
     */
    public function change_form_position(){
        if(!(isset($_POST['change_value'])&&!empty($_POST['change_value']))){
            return 'Valoarea cu care se schima pozitia formularului curent nu e stabilita';
        }
        $changeValue= $_POST['change_value'];
        $position=$_SESSION['admin']['form_position'];

        if(!is_numeric($changeValue)||($changeValue!=-1&&$changeValue!=1)){
            return 'Valoare pentru schimbarea pozitiei formularului nu este acceptabila';
        }

        $position+=$changeValue;

        $nrForms=count($_SESSION['admin']['list_forms']);

        if($position==-1){
            $position=$nrForms-1;
        }

        if($position==$nrForms){
            $position=0;
        }

        $_SESSION['admin']['form_position']=$position;

        return 'OK';
    }
}

?>