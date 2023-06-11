const URI = "./app/controllers";
const loadAreas = async () => {
  try {
    const response = await fetch(`${URI}/AreaController.php?action=getAreas`);

    if (response.ok) {
      const areas = await response.json();
      const select = document.querySelector("#area");
      select.innerHTML = "";
      const option = document.createElement("option");
      option.value = "";
      option.innerHTML = "Seleccione un área";
      select.appendChild(option);
      areas.forEach((area) => {
        const option = document.createElement("option");
        option.value = area.id;
        option.innerHTML = area.nombre;
        select.appendChild(option);
      });
    } else {
      console.error("Error al cargar áreas.");
    }
  } catch (err) {
    console.error("Error en la solicitud:", err);
  }
};

const loadRoles = async () => {
  try {
    const response = await fetch(`${URI}/RolController.php?action=getRoles`);

    if (response.ok) {
      const roles = await response.json();
      const checkbox = document.querySelector("#roles");
      checkbox.innerHTML = "";
      roles.forEach((rol) => {
        checkbox.appendChild(create_checkbox(rol));
      });
    } else {
      console.error("Error al cargar áreas.");
    }
  } catch (err) {
    console.error("Error en la solicitud:", err);
  }
};

const create_checkbox = (rol) => {
  const { id, nombre } = rol;
  const checkbox = document.createElement("input");
  checkbox.setAttribute("type", "checkbox");
  checkbox.setAttribute("class", "form-check-input");
  checkbox.setAttribute("id", "rol_" + id);
  checkbox.setAttribute("value", id);

  const label = document.createElement("label");
  label.setAttribute("class", "form-check-label");
  label.setAttribute("for", "opcion_" + id);
  label.textContent = nombre;

  const formCheck = document.createElement("div");
  formCheck.setAttribute("class", "form-check");
  formCheck.appendChild(checkbox);
  formCheck.appendChild(label);

  return formCheck;
};

const validarOpciones = () => {
  const opciones = document.querySelectorAll(
    '#grupo-opciones input[type="checkbox"]'
  );
  let seleccionado = false;

  opciones.forEach((opcion) => {
    if (opcion.checked) {
      seleccionado = true;
    }
  });

  if (!seleccionado) {
    alert("Debe seleccionar al menos un Rol.");
    return false;
  }

  return true;
};

const create_employee = async (id) => {
  const form = document.querySelector("form");
  const submitButton = form.querySelector('button[type="submit"]');
  form.addEventListener("submit", async (event) => {
    event.preventDefault();
    if (!validarOpciones()) {
      return;
    }
    submitButton.disabled = true;

    try {
      const formFields = form.elements;
      const roles = [];
      const rolFields = Array.from(formFields).filter((field) =>
        field.id.startsWith("rol_")
      );
      rolFields.forEach((field) => {
        if (field.checked) {
          roles.push(field.value);
        }
      });

      const data = new FormData(form);
      const jsonRoles = JSON.stringify(roles);
      data.append("roles", jsonRoles);
      const response = await fetch(`${URI}/EmployeeController.php`, {
        method: "POST",
        body: data,
      });

      if (response.ok) {
        const { status, msj, sql } = await response.json();
        const alert = document.querySelector("#msj_alert");
        if (status != "error") {
          alert.classList.add("alert-success");
          alert.classList.remove("alert-danger");
          if(!id) form.reset();
        } else {
          alert.classList.remove("alert-success");
          alert.classList.add("alert-danger");
        }
        alert.removeAttribute("hidden");
        alert.innerHTML = msj;
        submitButton.disabled = false;
      } else {
        const errorMessage = await response.text();
        console.error("Error al crear el empleado: " + errorMessage);
      }
    } catch (error) {
      // Mostrar mensaje de error en caso de que ocurra una excepción
      console.error("Error al crear el empleado: " + error.message);
    }

    // Habilitar nuevamente el botón de envío
    submitButton.disabled = false;
  });
};

const loadEmployee = async (id) => {
  try {
    const response = await fetch(
      `${URI}/EmployeeController.php?action=getEmployee&id=${id}`
    );

    if (response.ok) {
      const {id, identificacion, nombre, email, sexo, area, boletin, descripcion, roles} = await response.json();
      const form = document.querySelector("form");
      form.querySelector("#id").value = id;
      form.querySelector("#identificacion").value = identificacion;
      form.querySelector("#nombre").value = nombre;
      form.querySelector("#correo").value = email;
      if (sexo == "M") {
        form.querySelector("#sexo-m").checked = true;
      } else {
        form.querySelector("#sexo-f").checked = true;
      }
      const select = form.querySelector("#area");
      pre_seleccionar_area(select, area.id);
      form.querySelector("#boletin").checked = boletin;
      const descripcion_textarea = form.querySelector("#descripcion");
      descripcion_textarea.value = descripcion;

      const rolesContainer = document.querySelector("#roles");
      const checkboxes = rolesContainer.querySelectorAll("input[type='checkbox']");
      checkboxes.forEach((checkbox) => {
        const checkboxValue = checkbox.value;
        const roleExists = roles.some((role) => role.rol.id === checkboxValue);
      
        if (roleExists) {
          checkbox.checked = true;
        }
      })
    } else {
      console.error("Error al cargar los empleados.");
    }
  } catch (err) {
    console.error("Error en la solicitud:", err);
  }
};

const pre_seleccionar_area = (select, area) => {
  for (let i = 0; i < select.options.length; i++) {
    const option = select.options[i];
    
    if (option.value === area.toString()) {
      option.selected = true;
      break;
    }
  }
}

$(document).ready(function () {
  const id = document.querySelector("#id").value;
  loadAreas();
  create_employee(id);
  loadRoles();
  if (id != "") {
    loadEmployee(id);
  }
});
