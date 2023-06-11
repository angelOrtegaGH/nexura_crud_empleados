<?php
$action = (isset($_GET['id'])) ? 'update' : 'save';
$id = (isset($_GET['id'])) ? $_GET['id'] : null;
?>

<script src="./public/js/create.js"></script>
<div class="container">
    <h1 class="center">Crear empleado</h1>

    <div class="alert alert-info" role="alert">
        Los campos con asteriscos (*) son obligatorios
    </div>
    <div id="msj_alert" class="alert alert-danger" role="alert" hidden>
    </div>

    <form>
        <input type="hidden" value="<?= $action ?>" name="action" id="action">
        <input type="hidden" value="<?= $id ?>" name="id" id="id">
        <div class="form-group row">
            <label for="identificacion" class="col-sm-2 col-form-label">Identificación *</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="identificacion" id="identificacion" required pattern="\d{1,15}" min='1' title="La identificación debe tener de 1 a 15 dígitos numéricos">
            </div>
        </div>
        <div class="form-group row">
            <label for="nombre" class="col-sm-2 col-form-label">Nombre completo *</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="nombre" id="nombre" required maxlength="255">
            </div>
        </div>
        <div class="form-group row">
            <label for="correo" class="col-sm-2 col-form-label">Correo electrónico *</label>
            <div class="col-sm-10">
                <input type="email" class="form-control" name="correo" id="correo" required maxlength="255">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Sexo *</label>
            <div class="col-sm-10">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="sexo" id="sexo-m" value="M" required>
                    <label class="form-check-label" for="sexo-m">Masculino</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="sexo" id="sexo-f" value="F" required>
                    <label class="form-check-label" for="sexo-f">Femenino</label>
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label for="area" class="col-sm-2 col-form-label">Área *</label>
            <div class="col-sm-10">
                <select class="form-control" name="area" id="area" required>
                    <option value="">Seleccione un área</option>
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label for="descripcion" class="col-sm-2 col-form-label">Descripción *</label>
            <div class="col-sm-10">
                <textarea class="form-control" name="descripcion" id="descripcion" required></textarea>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-2"></div>
            <div class="col-sm-10">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" name="boletin" id="boletin">
                    <label class="form-check-label" for="boletin">Deseo recibir boletín informativo</label>
                </div>
            </div>
        </div>
        <div class="form-group row" id="grupo-opciones" >
            <label class="col-sm-2 col-form-label">Roles *</label>
            <div id="roles" class="col-sm-10">
            </div>
        </div>

        <div class="text-center">
            <div class="btn-group">
                <button type="submit" class="btn btn-primary">Guardar</button>
                <a href="?view=index.php">
                    <button type="button" class="btn btn-dark">Volver al listado</button>
                </a>
            </div>
        </div>
    </form>
</div>