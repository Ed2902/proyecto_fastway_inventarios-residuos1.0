document.getElementById('Producto').addEventListener('submit', function(event) {
    var isValid = true;
    var inputs = document.querySelectorAll('#Producto input[required]');
    inputs.forEach(function(input) {
        if (!input.value.trim()) {
            isValid = false;
            input.classList.add('is-invalid');
        } else {
            input.classList.remove('is-invalid');
        }
    });
    if (!isValid) {
        event.preventDefault(); // Prevent form submission if validation fails
        alert('Por favor, complete todos los campos obligatorios.');
    }
});

document.querySelector('.boton_cancelar').addEventListener('click', function() {
    document.getElementById('Producto').reset();
});