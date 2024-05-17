var inventarioData = [];  // Array para almacenar los datos del inventario

function agregarFilaTabla() {
    var codigoProducto = document.getElementById("codigoProducto").value;
    var nombre = document.getElementById("nombre").value;
    var referencia = document.getElementById("referencia").value;
    var tipo = document.getElementById("tipo").value;
    var quienDaIngreso = document.getElementById("quienDaIngreso").value;
    var kilos = document.getElementById("kilos").value; // Cambiado a kilos
    var cliente = document.getElementById("cliente").value;

    if (!codigoProducto || !nombre || !referencia || !tipo || !quienDaIngreso || !kilos || !cliente) {
        alert("Todos los campos son obligatorios. Por favor, complete todos los campos.");
        return;
    }

    // Expresión regular para aceptar números enteros y decimales
    if (!/^(\d+(\.\d+)?|\.\d+)$/.test(kilos)) {
        alert("Por favor, ingrese un número en el campo 'Kilos a Agregar'.");
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

    cell1.innerHTML = codigoProducto;
    cell2.innerHTML = nombre;
    cell3.innerHTML = referencia;
    cell4.innerHTML = tipo;
    cell5.innerHTML = quienDaIngreso;
    cell6.innerHTML = kilos; // Cambiado a kilos
    cell7.innerHTML = cliente;

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

    cell8.appendChild(actionsDiv);

    // Almacenar datos en el array
    var filaDatos = {
        CodigoProducto: codigoProducto,
        Nombre: nombre,
        Referencia: referencia,
        Tipo: tipo,
        QuienDaIngreso: quienDaIngreso,
        kilos: kilos, // Cambiado a kilos
        Cliente: cliente
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
    document.getElementById("codigoProducto").value = row.cells[0].innerHTML;
    document.getElementById("nombre").value = row.cells[1].innerHTML;
    document.getElementById("referencia").value = row.cells[2].innerHTML;
    document.getElementById("tipo").value = row.cells[3].innerHTML;
    document.getElementById("quienDaIngreso").value = row.cells[4].innerHTML;
    document.getElementById("kilos").value = row.cells[5].innerHTML; // Cambiado a kilos
    document.getElementById("cliente").value = row.cells[6].innerHTML;

    // Eliminar la fila al editar
    row.parentNode.removeChild(row);
}

function eliminarFila(button) {
    var row = button.parentNode.parentNode.parentNode;
    row.parentNode.removeChild(row);
}

function limpiarFormulario() {
    document.getElementById("codigoProducto").value = "";
    document.getElementById("nombre").value = "";
    document.getElementById("referencia").value = "";
    document.getElementById("tipo").value = "";
    document.getElementById("quienDaIngreso").value = "";
    document.getElementById("kilos").value = ""; // Cambiado a kilos
    document.getElementById("cliente").value = "";
}

// Función para hacer los campos de entrada no modificables, excepto ciertos campos
function hacerCamposNoModificablesExceptoAlgunos() {
    // Obtenemos todos los elementos de entrada dentro del formulario
    var camposEntrada = document.querySelectorAll('input[type="text"], input[type="number"]');
    
    // Iteramos sobre cada campo de entrada
    camposEntrada.forEach(function(campo) {
        // Verificamos si el campo no es alguno de los campos que queremos excluir
        if (campo.id !== 'codigoProducto' && campo.id !== 'quienDaIngreso' && campo.id !== 'kilos' && campo.id !== 'cliente') {
            // Hacemos que el campo sea no modificable
            campo.setAttribute('readonly', 'true');
        }
    });
}

// Función para obtener los datos para enviar al servidor
function obtenerDatosParaEnviar() {
    var datosParaEnviar = [];

    inventarioData.forEach(function(fila) {
        var datosFila = {
            id_productoFK: fila.CodigoProducto,
            id_usuarioFK: fila.QuienDaIngreso,
            cantidad: fila.kilos, // Cambiado a kilos
            cliente: fila.Cliente
        };
        
        datosParaEnviar.push(datosFila);
    });

    console.log('Datos para enviar:', datosParaEnviar);

    return datosParaEnviar;
}

// Llamamos a la función después de cargar el DOM
document.addEventListener('DOMContentLoaded', function() {
    hacerCamposNoModificablesExceptoAlgunos();
});

function enviarDatosAlServidor() {
    // Obtener los datos a enviar
    var datosParaEnviar = obtenerDatosParaEnviar();

    // Mostrar los datos que se van a enviar en la consola
    console.log('Datos a enviar:', datosParaEnviar);

    // Crear una solicitud AJAX
    var xhr = new XMLHttpRequest();
    var url = '../objetos_guardar/guardar_inventario.php';
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

    // Configurar la función de devolución de


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