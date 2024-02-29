var inventarioData = [];  // Array para almacenar los datos del inventario

function agregarFilaTabla() {
    var codigoProducto = document.getElementById("CodigoProducto").value;
    var nombre = document.getElementById("nombre").value; // Nuevo campo agregado
    var referencia = document.getElementById("Referencia").value;
    var tipo = document.getElementById("tipo").value;
    var marca = document.getElementById("Marca").value;
    var quienDaIngreso = document.getElementById("QuienDaIngreso").value;
    var Fw = document.getElementById("FW").value;
    var cantidadesAgregar = document.getElementById("CantidadesAgregar").value;

    if (!nombre || !referencia || !tipo || !marca || !quienDaIngreso || !Fw || !cantidadesAgregar) {
        alert("Todos los campos son obligatorios. Por favor, complete todos los campos.");
        return;
    }

    if (!/^\d+$/.test(cantidadesAgregar)) {
        alert("Por favor, ingrese un número entero en el campo 'Cantidades a Agregar'.");
        return;
    }

    var table = document.getElementById("tablaInventario");
    var newRow = table.insertRow(table.rows.length);

    var cell1 = newRow.insertCell(0);
    var cell2 = newRow.insertCell(1);
    var cell3 = newRow.insertCell(2);
    var cell4 = newRow.insertCell(3);
    var cell5 = newRow.insertCell(4);
    var cell6 = newRow.insertCell(5);
    var cell7 = newRow.insertCell(6);
    var cell8 = newRow.insertCell(7);
    var cell9 = newRow.insertCell(8); // Nueva celda para acciones

    cell1.innerHTML = codigoProducto;
    cell2.innerHTML = nombre; // Nuevo campo agregado
    cell3.innerHTML = referencia;
    cell4.innerHTML = tipo;
    cell5.innerHTML = marca;
    cell6.innerHTML = quienDaIngreso;
    cell7.innerHTML = Fw;
    cell8.innerHTML = cantidadesAgregar;

    // Crear botones de acciones
    var actionsDiv = document.createElement("div");
    actionsDiv.className = "btn-group";
    actionsDiv.role = "group";

    var editButton = document.createElement("button");
    editButton.className = "btn btn-warning btn-sm";
    editButton.type = "button";
    editButton.innerHTML = "Editar";
    editButton.onclick = function() { editarFila(this); };

    var deleteButton = document.createElement("button");
    deleteButton.className = "btn btn-danger btn-sm";
    deleteButton.type = "button";
    deleteButton.innerHTML = "Eliminar";
    deleteButton.onclick = function() { eliminarFila(this); };

    actionsDiv.appendChild(editButton);
    actionsDiv.appendChild(deleteButton);

    cell9.appendChild(actionsDiv); // Agregar acciones a la nueva celda

    // Almacenar datos en el array
    var filaDatos = {
        CodigoProducto: codigoProducto,
        Nombre: nombre, // Nuevo campo agregado
        Referencia: referencia,
        Tipo: tipo,  
        Marca: marca,
        QuienDaIngreso: quienDaIngreso,
        FW: Fw,
        CantidadesAgregar: cantidadesAgregar
    };

    inventarioData.push(filaDatos);

    limpiarFormulario();
}

function editarFila(button) {
    var row = button.parentNode.parentNode.parentNode;
    var rowIndex = row.rowIndex;

    // Actualizar el índice en el campo oculto
    document.getElementById("RowIndex").value = rowIndex;

    // Resto de tu código para llenar el formulario con los datos de la fila
    document.getElementById("CodigoProducto").value = row.cells[0].innerHTML;
    document.getElementById("nombre").value = row.cells[1].innerHTML; // Nuevo campo agregado
    document.getElementById("Referencia").value = row.cells[2].innerHTML;
    document.getElementById("tipo").value = row.cells[3].innerHTML;
    document.getElementById("Marca").value = row.cells[4].innerHTML;
    document.getElementById("QuienDaIngreso").value = row.cells[5].innerHTML;
    document.getElementById("FW").value = row.cells[6].innerHTML;
    document.getElementById("CantidadesAgregar").value = row.cells[7].innerHTML;

    // Eliminar la fila al editar
    row.parentNode.removeChild(row);
}

function eliminarFila(button) {
    var row = button.parentNode.parentNode.parentNode;
    row.parentNode.removeChild(row);
}

function limpiarFormulario() {
    document.getElementById("CodigoProducto").value = "";
    document.getElementById("nombre").value = ""; // Nuevo campo agregado
    document.getElementById("Referencia").value = "";
    document.getElementById("tipo").value = "";
    document.getElementById("Marca").value = "";
    document.getElementById("QuienDaIngreso").value = "";
    document.getElementById("FW").value = "";
    document.getElementById("CantidadesAgregar").value = "";
}

// Función para hacer los campos de entrada no modificables, excepto ciertos campos
function hacerCamposNoModificablesExceptoAlgunos() {
    // Obtenemos todos los elementos de entrada dentro del formulario
    var camposEntrada = document.querySelectorAll('input[type="text"], input[type="number"]');
    
    // Iteramos sobre cada campo de entrada
    camposEntrada.forEach(function(campo) {
        // Verificamos si el campo no es alguno de los campos que queremos excluir
        if (campo.id !== 'CodigoProducto' && campo.id !== 'QuienDaIngreso' && campo.id !== 'FW' && campo.id !== 'CantidadesAgregar') {
            // Hacemos que el campo sea no modificable
            campo.setAttribute('readonly', 'true');
        }
    });
}

// Llamamos a la función después de cargar el DOM
document.addEventListener('DOMContentLoaded', function() {
    hacerCamposNoModificablesExceptoAlgunos();
});


function enviarDatosAlServidor() {
    // Realizar una solicitud POST a tu script PHP
    fetch('../guardar/guardarproducto.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ inventarioData: inventarioData }),
    })
    .then(response => response.json())
    .then(data => {
        console.log('Respuesta del servidor:', data);
        // Puedes realizar más acciones después de recibir la respuesta del servidor
    })
    .catch(error => {
        console.error('Error al enviar datos al servidor:', error);
    });
}

// Agregar un event listener al botón "Enviar"
document.getElementById("enviarButton").addEventListener("click", function () {
    // Llamar a la función enviarDatosAlServidor al hacer clic en el botón
    enviarDatosAlServidor();
});
