
var ordenData = [];
function agregarFilaTabla() {
    var codigoProducto = document.getElementById("CodigoProducto").value;
    var nombre = document.getElementById("nombre").value;
    var referencia = document.getElementById("Referencia").value;
    var tipo = document.getElementById("tipo").value;
    var cantidadesAgregar = document.getElementById("CantidadesAgregar").value;

    if (!codigoProducto || !nombre || !referencia || !tipo || !cantidadesAgregar) {
        alert("Todos los campos son obligatorios. Por favor, complete todos los campos.");
        return;
    }

    if (!/^\d+$/.test(cantidadesAgregar)) {
        alert("Por favor, ingrese un número entero en el campo 'Cantidades a Agregar'.");
        return;
    }

    var table = document.getElementById("tablaorden"); // Cambio aquí
    var newRow = table.insertRow(table.rows.length);

    var cell1 = newRow.insertCell(0);
    var cell2 = newRow.insertCell(1);
    var cell3 = newRow.insertCell(2);
    var cell4 = newRow.insertCell(3);
    var cell5 = newRow.insertCell(4);
    var cell6 = newRow.insertCell(5);

    cell1.innerHTML = codigoProducto;
    cell2.innerHTML = nombre;
    cell3.innerHTML = referencia;
    cell4.innerHTML = tipo;
    cell5.innerHTML = cantidadesAgregar;

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

    cell6.appendChild(actionsDiv); 

    // Almacenar datos en el array
    var filaDatos = {
        CodigoProducto: codigoProducto,
        Nombre: nombre,
        Referencia: referencia,
        Tipo: tipo,  
        CantidadesAgregar: cantidadesAgregar
    };

    ordenData.push(filaDatos);

    limpiarFormulario(); // Llamada a la función aquí
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
    document.getElementById("CantidadesAgregar").value = row.cells[4].innerHTML;

    // Eliminar la fila al editar
    row.parentNode.removeChild(row);
}

function eliminarFila(button) {
    var row = button.parentNode.parentNode.parentNode;
    row.parentNode.removeChild(row);
}

function limpiarFormulario() {
    console.log("Limpiando el formulario...");
    document.getElementById("CodigoProducto").value = "";
    document.getElementById("nombre").value = "";
    document.getElementById("Referencia").value = "";
    document.getElementById("tipo").value = "";
    document.getElementById("CantidadesAgregar").value = "";
}

// Función para hacer los campos de entrada no modificables, excepto ciertos campos
function hacerCamposNoModificablesExceptoAlgunos() {
    // Obtenemos todos los elementos de entrada dentro del formulario
    var camposEntrada = document.querySelectorAll('input[type="text"], input[type="number"]');
    
    // Iteramos sobre cada campo de entrada
    camposEntrada.forEach(function(campo) {
        // Verificamos si el campo no es alguno de los campos que queremos excluir
        if (campo.id !== 'CodigoProducto' && campo.id !== 'CantidadesAgregar') {
            // Hacemos que el campo sea no modificable
            campo.setAttribute('readonly', 'true');
        }
    });
}

// Función para obtener los datos para enviar al servidor
function obtenerDatosParaEnviar() {
    var datosParaEnviar = [];

    ordenData.forEach(function(fila) {
        var datosFila = {
            cantidades: fila.CantidadesAgregar,
            id_productoFK: fila.CodigoProducto
        };
        
        datosParaEnviar.push(datosFila);
    });

    console.log('Datos para enviar:', datosParaEnviar);

    return datosParaEnviar;
}

document.addEventListener('DOMContentLoaded', function() {
    // Aquí puedes llamar a las funciones que necesites después de cargar el DOM
});

function enviarDatosAlServidor() {
    // Obtener los datos a enviar
    var datosParaEnviar = obtenerDatosParaEnviar();

    // Mostrar los datos que se van a enviar en la consola
    console.log('Datos a enviar:', datosParaEnviar);

    // Crear una solicitud AJAX
    var xhr = new XMLHttpRequest();
    var url = '../objetos_guardar/guardarorder.php';
    xhr.open('POST', url, true);
    xhr.setRequestHeader('Content-Type', 'application/json');

    // Convertir los datos a JSON
    var datosJSON = JSON.stringify(datosParaEnviar);

    // Configurar la función de devolución de llamada cuando la solicitud se complete
    xhr.onload = function () {
        if (xhr.status === 200) {
            // La solicitud fue exitosa
            console.log('Datos enviados correctamente al servidor.');
            // Puedes agregar aquí más acciones si lo deseas, como mostrar un mensaje de éxito al usuario
        } else {
            // Hubo un error en la solicitud
            console.error('Error al enviar datos al servidor. Código de estado:', xhr.status);
            // Puedes agregar aquí más acciones si lo deseas, como mostrar un mensaje de error al usuario
        }
    };

    // Configurar la función de devolución de llamada para errores de red
    xhr.onerror = function () {
        // Hubo un error de red
        console.error('Error de red al enviar datos al servidor.');
        // Puedes agregar aquí más acciones si lo deseas, como mostrar un mensaje de error al usuario
    };

    // Enviar la solicitud con los datos JSON
    xhr.send(datosJSON);
}