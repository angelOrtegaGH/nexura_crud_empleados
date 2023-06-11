<?php
require_once '../../includes/db_connection.php';
require_once '../Helpers/ErrorHelper.php';
require_once '../models/Area.php';
require_once '../models/EmployeeRol.php';

class Employee {
    private $id;
    private $identificacion;
    private $nombre;
    private $email;
    private $sexo;
    private $area_id;
    private $boletin;
    private $descripcion;
    private $roles;

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

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getIdentificacion() {
        return $this->identificacion;
    }

    public function setIdentificacion($identificacion) {
        $this->identificacion = $identificacion;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }
    
    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function getSexo() {
        switch ($this->sexo) {
            case 'M':
                $sexo = "Masculino";
                break;
            case 'F':
                $sexo = "Femenino";
                break;
            default:
                $sexo = "Desconocido";
                break;
        }
        return $sexo;
    }

    public function setSexo($sexo) {
        $this->sexo = $sexo;
    }

    public function getAreaId() {
        return $this->area_id;
    }

    public function setAreaId($area_id) {
        $this->area_id = $area_id;
    }

    public function getArea(){
        return new Area('id', $this->area_id);
    }

    public function getBoletinString() {
        return ($this->boletin) ? 'Si':'No';
    }

    public function getBoletin() {
        return $this->boletin;
    }

    public function setBoletin($boletin) {
        $this->boletin = $boletin;
    }

    public function getDescripcion() {
        return $this->descripcion;
    }

    public function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
    }

    public function getRoles() {
        return EmployeeRol::getEmployeRoles($this->id);
    }

    public function setRoles($roles) {
        $this->roles = json_decode($roles);
    }

    public function toArray() {
        return [
            'id' => $this->getId(),
            'identificacion' => $this->getIdentificacion(),
            'nombre' => $this->getNombre(),
            'email' => $this->getEmail(),
            'sexo' => $this->getSexo(),
            'area' => $this->getArea()->toArray(),
            'boletin' => $this->getBoletin(),
            'boletin_string' => $this->getBoletinString(),
            'descripcion' => $this->getDescripcion(),
            'roles' => $this->getRoles(),
        ];
    }

    public function save() {
        $data = (object) array();
        $identificacion = $this->identificacion;
        $nombre = $this->nombre;
        $email = $this->email;
        $sexo = $this->sexo;
        $area_id = $this->area_id;
        $boletin = $this->boletin;
        $descripcion = $this->descripcion;
        $roles = $this->roles;

        $query = ($this->id) ? 
                        "UPDATE empleado SET identificacion='$identificacion', nombre='$nombre', email='$email', sexo='$sexo', area_id='$area_id', boletin='$boletin', descripcion='$descripcion' WHERE id='". $this->id ."'" : 
                        "INSERT INTO empleado (identificacion, nombre, email, sexo, area_id, boletin, descripcion) VALUES ('$identificacion', '$nombre', '$email', '$sexo', '$area_id', '$boletin', '$descripcion')";
        
        $accion = ($this->id) ? 'actualizado' : 'creado';
        
        try {
            
            Connector::executeQuery($query, null);
            $employeRol = new EmployeeRol(null, null);
            $new_id = Employee::getEmployeeByIdentificacion($identificacion)->id;
            $response = $employeRol->saveRoles($new_id, $roles);
            if ($response->status != 'success') {
                $data->status = "error";
                $data->msj = $response->msj;
                $data->sql = $response->sql;
            }else{
                $data->status = "success";
                $data->msj = "Empleado $accion correctamente";
            }
        } catch (\Throwable $th) {
            $code = $th->getCode();
            $data->status = "error";
            $data->msj = errors_sql($code, "Empleado", " identificación '$identificacion'");
            $data->sql = $query;
        }
        return $data;
    }

    public static function getEmployees($id){
        $where = ($id) ? "WHERE id ='$id'" : null;
        $query = "SELECT id, identificacion, nombre, email, sexo, area_id, boletin, descripcion FROM empleado $where";
        $data = Connector::executeQuery($query, null);
        $employees = [];
        if(is_array($data)){
            foreach ($data as $row) {
                $employee = new Employee(null, null);
                $employee->setId($row['id']);
                $employee->setIdentificacion($row['identificacion']);
                $employee->setNombre($row['nombre']);
                $employee->setEmail($row['email']);
                $employee->setSexo($row['sexo']);
                $employee->setAreaId($row['area_id']);
                $employee->setBoletin($row['boletin']);
                $employee->setDescripcion($row['descripcion']);
                $employees[] = $employee;
            }
        }
        return $employees;
    }
    

    public static function getEmployeeById($id) {
        $employee = Employee::getEmployees($id);
        return ($employee) ? $employee[0] : null;
    }

    public static function getEmployeeByIdentificacion($identificacion) {
        return new Employee('identificacion', $identificacion);
    }

    public function delete() {
        $id = $this->id;
        $identificacion = $this->identificacion;
        $data = (object) array();
        $query = "DELETE FROM empleado WHERE id='$id'";
        try {
            Connector::executeQuery($query, null);
            $data->status = "success";
            $data->msj = "Empleado eliminado correctamente";
        } catch (\Throwable $th) {
            $code = $th->getCode();
            $data->status = "error";
            $data->msj = errors_sql($code, "Empleado", " identificación '$identificacion'");
        }
        return $data;
    }

}

?>
