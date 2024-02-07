function cambiarColorIcono(inputId, iconoId) {
    var input = document.getElementById(inputId);
    var icono = document.getElementById(iconoId);

    // Agrega un evento de cambio al campo de tipo file
    input.addEventListener('change', function () {
        // Verifica si se seleccionó un archivo
        if (input.files.length > 0) {
            // Cambia el color del icono a verde
            icono.style.color = '#28a745'; // Puedes ajustar el color según tu preferencia
            icono.classList.add('archivo-seleccionado'); // Agrega la clase al icono
        } else {
            // Si no hay archivo seleccionado, vuelve al color original
            icono.style.color = ''; // Vacía el color para que se use el estilo predeterminado
            icono.classList.remove('archivo-seleccionado'); // Elimina la clase del icono
        }
    });
}

// Llama a la función para cada par de campos de tipo file e icono
cambiarColorIcono('Camara', 'camaraLabel');
cambiarColorIcono('rut', 'rutLabel');
cambiarColorIcono('Representante', 'representanteLabel');
cambiarColorIcono('comercial', 'comercialLabel');
cambiarColorIcono('bancaria', 'bancariaLabel');
cambiarColorIcono('Circular', 'circularLabel');

function validarCamposObligatorios() {
    var nit = document.getElementById('Nit').value.trim();
    var nombre = document.getElementById('Nombre').value.trim();
    var direccion = document.getElementById('Dirección').value.trim();
    var telefono = document.getElementById('Teléfono').value.trim();
    var camara = document.getElementById('Camara').value.trim();
    var rut = document.getElementById('rut').value.trim();

    return !(nit === '' && nombre === '' && direccion === '' && telefono === '' && camara === '' && rut === '');
}

function validarTelefono() {
    var telefono = document.getElementById('Teléfono').value.trim();
    return telefono.length === 10 && !isNaN(telefono);
}

function validarDocumentos() {
    var archivosSeleccionados = document.querySelectorAll('.archivo-seleccionado');
    return archivosSeleccionados.length > 0;
}

function validarFormulario() {
    var nit = document.getElementById('Nit').value.trim();
    var nombre = document.getElementById('Nombre').value.trim();
    var direccion = document.getElementById('Dirección').value.trim();
    var telefono = document.getElementById('Teléfono').value.trim();
    var camara = document.getElementById('Camara').value.trim();
    var rut = document.getElementById('rut').value.trim();

    // Verifica que todos los campos sean obligatorios
    if (nit === '' || nombre === '' || direccion === '' || telefono === '' || camara === '' || rut === '') {
        alert('Todos los campos son obligatorios. Por favor, llene todos los campos.');
        return false;
    }

    // Validación del número de teléfono
    if (telefono.length !== 10 || isNaN(telefono)) {
        alert('El número de teléfono debe tener exactamente 10 dígitos numéricos.');
        return false;
    }

    // Verifica que al menos un documento haya sido seleccionado
    var archivosSeleccionados = document.querySelectorAll('.archivo-seleccionado');
    if (archivosSeleccionados.length === 0) {
        alert('Al menos un documento debe ser cargado. Por favor, cargue al menos un documento.');
        return false;
    }

    // Si todas las validaciones pasan, el formulario se enviará
    alert('Formulario enviado con éxito!');
    return true;
}
