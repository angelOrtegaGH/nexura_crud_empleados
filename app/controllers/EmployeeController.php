<?php
// EmployeeController.php

require_once '../models/Employee.php';

class EmployeeController {

    public function index() {
        $employees = Employee::getEmployees(null);
        $employeeArray = [];

        foreach ($employees as $employee) {
            $employeeArray[] = $employee->toArray();
        }

        echo json_encode($employeeArray);
    }

    public function employee($id) {
        $employee = Employee::getEmployeeById($id);
        echo json_encode($employee->toArray());
    }

    public function create($id) {
        $identificacion = $_POST['identificacion'];
        $nombre = $_POST['nombre'];
        $email = $_POST['correo'];
        $sexo = $_POST['sexo'];
        $area_id = $_POST['area'];
        $boletin = isset($_POST['boletin']) ? 1 : 0;
        $descripcion = $_POST['descripcion'];
        $roles = $_POST['roles'];

        $employee = new Employee(null, null);
        $employee->setId($id);
        $employee->setIdentificacion($identificacion);
        $employee->setNombre($nombre);
        $employee->setEmail($email);
        $employee->setSexo($sexo);
        $employee->setAreaId($area_id);
        $employee->setBoletin($boletin);
        $employee->setDescripcion($descripcion);
        $employee->setRoles($roles);

        echo json_encode($employee->save());
    }

    public function edit($id) {
        // Obtener el empleado por su ID desde la base de datos
        $employee = Employee::getEmployeeById($id);

        // Verificar si el empleado existe
        if (!$employee) {
            // Redireccionar al listado de empleados si el empleado no existe
            header('Location: index.php');
            exit;
        }

        // Verificar si se envió el formulario de edición
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Obtener los datos del formulario
            $identificacion = $_POST['identificacion'];
            $nombre = $_POST['nombre'];
            $email = $_POST['email'];
            $sexo = $_POST['sexo'];
            $area_id = $_POST['area_id'];
            $boletin = isset($_POST['boletin']) ? 1 : 0;
            $descripcion = $_POST['descripcion'];

            // Actualizar los datos del empleado
            $employee->setIdentificacion($identificacion);
            $employee->setNombre($nombre);
            $employee->setEmail($email);
            $employee->setSexo($sexo);
            $employee->setAreaId($area_id);
            $employee->setBoletin($boletin);
            $employee->setDescripcion($descripcion);

            // Guardar los cambios en la base de datos
            $employee->save();

            // Redireccionar al listado de empleados
            header('Location: index.php');
            exit;
        }

        // Cargar la vista edit.php para mostrar el formulario de edición
        require 'views/edit.php';
    }

    public function delete($id) {
        $data = (object) array();
        $employee = Employee::getEmployeeById($id);
        if ($employee) {
            echo json_encode($employee->delete());
        }else{
            $data->status = "error";
            $data->msj = "El empleado con identificacion ". $employee->getIdentificacion() ." no existe";
        }
    }

    public function error(){
        require '../views/error.html';
    }
}

$action = (isset($_GET['action'])) ? $_GET['action'] : $_POST['action'];
$controller = new EmployeeController();
switch ($action) {
    case 'getEmployees':
        return $controller->index();
        break;
    case 'save':
        return $controller->create(null);
        break;
    case 'update':
        $id=$_POST['id'];
        return $controller->create($id);
        break;
    case 'delete':
        $id=$_GET['id'];
        return $controller->delete($id);
        break;
    case 'getEmployee':
        $id=$_GET['id'];
        return $controller->employee($id);
        break;
    
    default:
        //return $controller->error();
        break;
}
