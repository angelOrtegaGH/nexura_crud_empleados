-- query´s para modificacion bd original

ALTER TABLE empleado ADD COLUMN identificacion VARCHAR(15); -- creacion de campo identificacion
ALTER TABLE empleado MODIFY COLUMN identificacion VARCHAR(15) NOT NULL; -- creacion restriccion no nulo para identificacion
UPDATE empleado SET identificacion = UUID(); -- asignacion  uid a los empleados registrados
ALTER TABLE empleado ADD CONSTRAINT unique_identificacion UNIQUE (identificacion); -- creacion restriccion unico para identificacion