<?php

require_once '../models/Area.php';

class AreaController {

    public function index() {
        $areas = Area::getAreas(null);
        $areaArray = [];
        foreach ($areas as $area) {
            $areaArray[] = $area->toArray();
        }
        echo json_encode($areaArray);
    }

    public function error(){
        require '../views/error.html';
    }
}

$action = (isset($_GET['action'])) ? $_GET['action'] : $_POST['action'];
$controller = new AreaController();
switch ($action) {
    case 'getAreas':
        return $controller->index();
        break;
    
    default:
        //return $controller->error();
        break;
}
