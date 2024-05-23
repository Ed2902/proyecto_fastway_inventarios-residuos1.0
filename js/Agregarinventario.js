var inventarioData = [];  // Array para almacenar los datos del inventario

function agregarFilaTabla() {
    var id_productoFK = document.getElementById("id_productoFK").value; // Cambiado a id_productoFK
    var nombre = document.getElementById("nombre").value;
    var referencia = document.getElementById("referencia").value;
    var tipo = document.getElementById("tipo").value;
    var id_usuarioFK = document.getElementById("id_usuarioFK").value; // Cambiado a id_usuarioFK
    var peso = document.getElementById("peso").value; // Cambiado a peso
    var id_proveedorFK = document.getElementById("id_proveedorFK").value; // Cambiado a id_proveedorFK
    var valorPorKilo = document.getElementById("valorPorKilo").value; // Nuevo campo

    if (!id_productoFK || !nombre || !referencia || !tipo || !id_usuarioFK || !peso || !id_proveedorFK || !valorPorKilo) {
        alert("Todos los campos son obligatorios. Por favor, complete todos los campos.");
        return;
    }

    // Expresión regular para aceptar números enteros y decimales
    if (!/^(\d+(\.\d+)?|\.\d+)$/.test(peso)) {
        alert("Por favor, ingrese un número en el campo 'Peso a Agregar'.");
        return;
    }

    // Expresión regular para aceptar números enteros y decimales para valorPorKilo
    if (!/^(\d+(\.\d+)?|\.\d+)$/.test(valorPorKilo)) {
        alert("Por favor, ingrese un número en el campo 'Valor por Kilo'.");
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
    var cell9 = newRow.insertCell(8);

    cell1.innerHTML = id_productoFK; // Cambiado a id_productoFK
    cell2.innerHTML = nombre;
    cell3.innerHTML = referencia;
    cell4.innerHTML = tipo;
    cell5.innerHTML = id_usuarioFK; // Cambiado a id_usuarioFK
    cell6.innerHTML = peso; // Cambiado a peso
    cell7.innerHTML = id_proveedorFK; // Cambiado a id_proveedorFK
    cell8.innerHTML = valorPorKilo; // Nuevo campo

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

    cell9.appendChild(actionsDiv);

    // Almacenar datos en el array
    var filaDatos = {
        id_productoFK: id_productoFK, // Cambiado a id_productoFK
        Nombre: nombre,
        Referencia: referencia,
        Tipo: tipo,
        id_usuarioFK: id_usuarioFK, // Cambiado a id_usuarioFK
        peso: peso, // Cambiado a peso
        id_proveedorFK: id_proveedorFK, // Cambiado a id_proveedorFK
        valorPorKilo: valorPorKilo // Nuevo campo
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
    document.getElementById("id_productoFK").value = row.cells[0].innerHTML; // Cambiado a id_productoFK
    document.getElementById("nombre").value = row.cells[1].innerHTML;
    document.getElementById("referencia").value = row.cells[2].innerHTML;
    document.getElementById("tipo").value = row.cells[3].innerHTML;
    document.getElementById("id_usuarioFK").value = row.cells[4].innerHTML; // Cambiado a id_usuarioFK
    document.getElementById("peso").value = row.cells[5].innerHTML; // Cambiado a peso
    document.getElementById("id_proveedorFK").value = row.cells[6].innerHTML; // Cambiado a id_proveedorFK
    document.getElementById("valorPorKilo").value = row.cells[7].innerHTML; // Nuevo campo

    // Eliminar la fila al editar
    row.parentNode.removeChild(row);
}

function eliminarFila(button) {
    var row = button.parentNode.parentNode.parentNode;
    row.parentNode.removeChild(row);
}

function limpiarFormulario() {
    document.getElementById("id_productoFK").value = ""; // Cambiado a id_productoFK
    document.getElementById("nombre").value = "";
    document.getElementById("referencia").value = "";
    document.getElementById("tipo").value = "";
    document.getElementById("id_usuarioFK").value = ""; // Cambiado a id_usuarioFK
    document.getElementById("peso").value = ""; // Cambiado a peso
    document.getElementById("id_proveedorFK").value = ""; // Cambiado a id_proveedorFK
    document.getElementById("valorPorKilo").value = ""; // Nuevo campo
}

// Función para hacer los campos de entrada no modificables, excepto ciertos campos
function hacerCamposNoModificablesExceptoAlgunos() {
    // Obtenemos todos los elementos de entrada dentro del formulario
    var camposEntrada = document.querySelectorAll('input[type="text"], input[type="number"]');
    
    // Iteramos sobre cada campo de entrada
    camposEntrada.forEach(function(campo) {
        // Verificamos si el campo no es alguno de los campos que queremos excluir
        if (campo.id !== 'id_productoFK' && campo.id !== 'id_usuarioFK' && campo.id !== 'peso' && campo.id !== 'id_proveedorFK' && campo.id !== 'valorPorKilo') { // Nuevo campo
            // Hacemos que el campo sea no modificable
            campo.setAttribute('readonly', 'true');
        }
    });
}

// Llamamos a la función después de cargar el DOM
document.addEventListener('DOMContentLoaded', function() {
    hacerCamposNoModificablesExceptoAlgunos();
});
// Función para obtener los datos para enviar al servidor
function obtenerDatosParaEnviar() {
    var datosParaEnviar = inventarioData.map(fila => ({
        peso: fila.peso,
        valorkilo: fila.valorPorKilo,
        id_proveedorFK: fila.id_proveedorFK,
        id_productoFK: fila.id_productoFK,
        id_usuarioFK: fila.id_usuarioFK,
        id_ingresoFK: null // No tenemos este valor aquí
    }));

    console.log('Datos para enviar:', datosParaEnviar);

    return datosParaEnviar;
}


function enviarDatosAlServidor() {
    if (inventarioData.length === 0) {
        alert("No hay datos en la tabla para enviar.");
        return;
    } else {
        // Obtener los datos a enviar
        var datosParaEnviar = obtenerDatosParaEnviar();

        // Mostrar los datos que se van a enviar en la consola
        console.log('Datos a enviar:', datosParaEnviar);

        // Convertir los datos a JSON y mostrar la cadena JSON en la consola antes de enviarla al servidor
        var datosJSON = JSON.stringify(datosParaEnviar);
        console.log('Datos JSON a enviar:', datosJSON);

        // Crear una solicitud AJAX
        var xhr = new XMLHttpRequest();
        var url = '../objetos_guardar/guardar_inventario.php';
        xhr.open('POST', url, true);
        xhr.setRequestHeader('Content-Type', 'application/json');

        // Configurar la función de devolución de llamada cuando la solicitud se complete
        xhr.onload = function () {
            if (xhr.status === 200) {
                // La solicitud fue exitosa
                console.log('Datos enviados correctamente al servidor.');
                // Puedes agregar aquí más acciones si lo deseas, como mostrar un mensaje de éxito al usuario
                alert('Datos enviados correctamente al servidor.');
            } else {
                // Hubo un error en la solicitud
                console.error('Error al enviar datos al servidor. Código de estado:', xhr.status);
                // Puedes agregar aquí más acciones si lo deseas, como mostrar un mensaje de error al usuario
                alert('Error al enviar datos al servidor. Código de estado: ' + xhr.status);
            }
        };

        // Configurar la función de devolución de llamada para errores de red
        xhr.onerror = function () {
            // Hubo un error de red
            console.error('Error de red al enviar datos al servidor.');
            // Puedes agregar aquí más acciones si lo deseas, como mostrar un mensaje de error al usuario
            alert('Error de red al enviar datos al servidor.');
        };

        // Enviar la solicitud con los datos JSON
        xhr.send(datosJSON);
    }
}
