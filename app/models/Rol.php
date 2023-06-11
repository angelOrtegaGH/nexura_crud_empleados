<?php
require_once '../../includes/db_connection.php';
class Rol {
    private $id;
    private $nombre;

    function __construct($field, $value) {
        if ($field != null) {
            if (is_array($field)) {
                foreach ($field as $Var => $Val) {
                    $this->$Var = $Val;
                }
            } else {
                $query = "SELECT id, nombre FROM roles WHERE $field = '$value'";
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

    public static function getRoles($id){
        $where = ($id) ? "WHERE id ='$id'" : null;
        $query = "SELECT id, nombre FROM roles $where";
        $data = Connector::executeQuery($query, null);
        $roles = [];
        if(is_array($data)){
            foreach ($data as $row) {
                $rol = new Rol(null, null);
                $rol->setId($row['id']);
                $rol->setNombre($row['nombre']);
                $roles[] = $rol;
            }
        }
        return $roles;
    }
    

    public static function getRolById($id) {
        $roles = Rol::getRoles($id);
        return ($roles) ? $roles[0] : null;
    }
}

?>
