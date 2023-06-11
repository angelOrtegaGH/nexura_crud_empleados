<?php
require_once '../../includes/db_connection.php';
require_once '../models/Rol.php';

class EmployeeRol {
    private $id_rol;
    private $id_empleado;

    function __construct($field, $value) {
        if ($field != null) {
            if (is_array($field)) {
                foreach ($field as $Var => $Val) {
                    $this->$Var = $Val;
                }
            } else {
                $query = "SELECT id_rol, id_empleado FROM empleado_rol WHERE $field = '$value'";
                $result = Connector::executeQuery($query, null);
                if (is_array($result) && count($result) > 0) {
                    foreach ($result[0] as $Var => $Val) {
                        $this->$Var = $Val;
                    }
                }
            }
        }
    }    

    public function getIdRol() {
        return $this->id_rol;
    }

    public function getRol() {
        return new Rol('id', $this->id_rol);
    }

    public function setIdRol($id) {
        $this->id_rol = $id;
    }

    public function getIdEmpleado() {
        return $this->id_empleado;
    }

    public function setIdEmpleado($id) {
        $this->id_empleado = $id;
    }
        
    public function toArray() {
        return [
            'id_empleado' => $this->id_empleado,
            'rol' => $this->getRol()->toArray(),
        ];
    }

    public function saveRoles($id_empleado, $roles) {
        $data = (object) array();
        $roles = array_map(function($item) use ($id_empleado) {
            return " $id_empleado ,$item";
        }, $roles);
        $values = '(' . implode('),(', $roles) . ')';

        $query_del = "delete from empleado_rol where empleado_id = $id_empleado ";
        $query = "INSERT INTO empleado_rol (empleado_id, rol_id) VALUES $values";
        try {
            Connector::executeQuery($query_del, null);
            Connector::executeQuery($query, null);
            $data->status = "success";
            $data->msj = "Roles creados correctamente";
        } catch (\Throwable $th) {
            $code = $th->getCode();
            $data->status = "error";
            $data->msj = errors_sql($code, "Roles", " roles '". json_encode($roles)."'");
            $data->sql = $query;
        }
        return $data;
    }

    public static function getEmployeRoles($id_empleado){
        $where = ($id_empleado) ? "WHERE empleado_id ='$id_empleado'" : null;
        $query = "SELECT empleado_id, rol_id FROM empleado_rol $where";
        $data = Connector::executeQuery($query, null);
        $empleado_roles = [];
        if(is_array($data)){
            foreach ($data as $row) {
                $empleado_rol = new EmployeeRol(null, null);
                $empleado_rol->setIdEmpleado($row['empleado_id']);
                $empleado_rol->setIdRol($row['rol_id']);
                $empleado_roles[] = $empleado_rol->toArray();
            }
        }
        return $empleado_roles;
    }
    
}

?>
