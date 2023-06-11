<?php
require_once '../../includes/db_connection.php';
class Area {
    private $id;
    private $nombre;

    function __construct($field, $value) {
        if ($field != null) {
            if (is_array($field)) {
                foreach ($field as $Var => $Val) {
                    $this->$Var = $Val;
                }
            } else {
                $query = "SELECT id, nombre FROM areas WHERE $field = '$value'";
                $result = Connector::executeQuery($query, null);
                if (is_array($result) && count($result) > 0) {
                    foreach ($result[0] as $Var => $Val) {
                        $this->$Var = $Val;
                    }
                }
            }
        }
    }    

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }
        
    public function toArray() {
        return [
            'id' => $this->id,
            'nombre' => $this->nombre
        ];
    }

    public static function getAreas($id){
        $where = ($id) ? "WHERE id ='$id'" : null;
        $query = "SELECT id, nombre FROM areas $where";
        $data = Connector::executeQuery($query, null);
        $areas = [];
        if(is_array($data)){
            foreach ($data as $row) {
                $area = new Area(null, null);
                $area->setId($row['id']);
                $area->setNombre($row['nombre']);
                $areas[] = $area;
            }
        }
        return $areas;
    }
    

    public static function getAreaById($id) {
        $area = Area::getAreas($id);
        return ($area) ? $area[0] : null;
    }
}

?>
