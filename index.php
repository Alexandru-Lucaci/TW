<?PHP

    session_start();
    if(!isset($_SESSION['loggedIn'])){
        $_SESSION['loggedIn']=0;
    }

    define ('DS', DIRECTORY_SEPARATOR);
    define ('HOME', dirname(__FILE__));


    require_once HOME . DS . 'config.php';
    require_once HOME . DS . 'utils' . DS . 'autoload.php'; 
    require_once HOME . DS . 'utils' . DS . 'bootstrap.php';  
?>