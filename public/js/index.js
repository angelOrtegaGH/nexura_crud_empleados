const URI = "./app/controllers/EmployeeController.php";
const loadEmployees = async () => {
  try {
    const response = await fetch(`${URI}?action=getEmployees`);

    if (response.ok) {
      const employees = await response.json();
      const tableBody = document.querySelector("#employeeTable tbody");
      tableBody.innerHTML = "";
      employees.forEach((employee) => {
        const row = document.createElement("tr");

        const accesos = employee.roles.map((role) => role.rol.nombre);
        const rolesString = accesos.join(", ");
        row.innerHTML = `
            <td>${employee.identificacion}</td>
            <td>${employee.nombre}</td>
            <td>${employee.email}</td>
            <td>${employee.sexo}</td>
            <td>${employee.area.nombre}</td>
            <td>${employee.boletin_string}</td>
            <td>(${rolesString})</td>
            <td>
              <button class="btn btn-primary" onclick="editEmployee(${employee.id})">
                Modificar
              </button>
            </td>
            <td>
              <button class="btn btn-danger" onclick="deleteEmployee(${employee.id})">
                Eliminar
              </button>
            </td>
          `;

        tableBody.appendChild(row);
      });
    } else {
      console.error("Error al cargar los empleados.");
    }
  } catch (err) {
    console.error("Error en la solicitud:", err);
  }
};

const deleteEmployee = async (id) => {
  if (confirm("¿Estás seguro de que deseas eliminar este empleado?")) {
    const response = await fetch(`${URI}/?action=delete&id=${id}`, {
      method: "DELETE",
    });

    if (response.ok) {
      const { status, msj, sql } = await response.json();
      const alert = document.querySelector("#msj_alert");
      if (status != "error") {
        alert.classList.add("alert-success");
        alert.classList.remove("alert-danger");
      } else {
        alert.classList.remove("alert-success");
        alert.classList.add("alert-danger");
      }
      alert.removeAttribute("hidden");
      alert.innerHTML = msj;
      loadEmployees()
    } else {
      const errorMessage = await response.text();
      console.error("Error al crear el empleado: " + errorMessage);
    }
  }
};

function editEmployee(id) {
  window.location.href = `?view=create.php&id=${id}`;
}

loadEmployees();
