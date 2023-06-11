<script src="./public/js/index.js"></script>

<div class="container-fluid">
    <h1 class="mt-4">Lista de empleados</h1>
    <div id="msj_alert" class="alert alert-danger" role="alert" hidden>
    </div>
    <div class="text-right mb-3">
        <a href="?view=create.php" class="btn btn-primary">
            <i class="fas fa-plus"></i> Crear
        </a>
    </div>
    <table id="employeeTable" class="table table-striped">
        <thead>
            <tr>
                <th>Identificación <i class="fas fa-fingerprint"></i></th>
                <th>Nombre <i class="fas fa-user"></i></th>
                <th>Email <i class="fas fa-envelope"></i></th>
                <th>Sexo <i class="fas fa-venus-mars"></i></th>
                <th>Área <i class="fas fa-building"></i></th>
                <th>Boletín <i class="fas fa-envelope-open-text"></i></th>
                <th>Roles <i class="fas  fa-address-card"></i></th>
                <th>Modificar <i class="fas fa-edit"></i></th>
                <th>Eliminar <i class="fas fa-trash"></i></th>
            </tr>
        </thead>
        <tbody>
            <!-- Aquí se generarán los registros de empleados -->
        </tbody>
    </table>
</div>