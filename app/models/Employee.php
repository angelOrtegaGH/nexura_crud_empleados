<?php
// employee.php
require_once('../includes/db_connection.php');

class Employee {
    private $id;
    private $identificacion;
    private $nombre;
    private $email;
    private $sexo;
    private $area_id;
    private $boletin;
    private $descripcion;

    function __construct($field, $value) {
        if ($field != null) {
            if (is_array($field)) {
                foreach ($field as $Var => $Val) {
                    $this->$Var = $Val;
                }
            } else {
                $query = "SELECT id, identificacion, nombre, email, sexo, area_id, boletin, descripcion FROM empleado WHERE $field = '$value'";
                $result = Connector::executeQuery($query, null);
                if (is_array($result) && count($result) > 0) {
                    foreach ($result[0] as $Var => $Val) {
                        $this->$Var = $Val;
                    }
                }
            }
        }
    }    

    // Getter y Setter para id
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    // Getter y Setter para identificacion
    public function getIdentificacion() {
        return $this->identificacion;
    }

    public function setIdentificacion($identificacion) {
        $this->identificacion = $identificacion;
    }

    // Getter y Setter para nombre
    public function getNombre() {
        return $this->nombre;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    // Getter y Setter para email
    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    // Getter y Setter para sexo
    public function getSexo() {
        return $this->sexo;
    }

    public function setSexo($sexo) {
        $this->sexo = $sexo;
    }

    // Getter y Setter para area_id
    public function getAreaId() {
        return $this->area_id;
    }

    public function setAreaId($area_id) {
        $this->area_id = $area_id;
    }

    // Getter y Setter para boletin
    public function getBoletin() {
        return $this->boletin;
    }

    public function setBoletin($boletin) {
        $this->boletin = $boletin;
    }

    // Getter y Setter para descripcion
    public function getDescripcion() {
        return $this->descripcion;
    }

    public function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
    }
}

$empleado = new Employee('id', 1);
print_r($empleado);
?>
