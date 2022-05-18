<?php

class HomeModel extends Model{

    function __construct(){
        parent::__construct();
    }

    public function getAllUsersInfo(){
        $this->setQuery("select * from users");
        return $this->getAll();
    }
}

?>