<?php

require_once '../models/Rol.php';

class RolController {

    public function index() {
        $roles = Rol::getRoles(null);
        $rolArray = [];
        foreach ($roles as $rol) {
            $rolArray[] = $rol->toArray();
        }
        echo json_encode($rolArray);
    }

    public function error(){
        require '../views/error.html';
    }
}

$action = (isset($_GET['action'])) ? $_GET['action'] : $_POST['action'];
$controller = new RolController();
switch ($action) {
    case 'getRoles':
        return $controller->index();
        break;
    
    default:
        //return $controller->error();
        break;
}
