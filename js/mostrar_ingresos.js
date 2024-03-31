// Función para cargar la tabla de ingresos al cargar la página
window.onload = function() {
    cargarTablaIngresos();
};

// Función para cargar la tabla de ingresos desde el servidor
function cargarTablaIngresos() {
    var tablaIngresos = document.getElementById("tablaIngresos");
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            tablaIngresos.innerHTML = this.responseText;
            // Agregar evento clic a cada celda de la tabla
            var cells = document.querySelectorAll('#tablaIngresos td');
            cells.forEach(cell => {
                cell.addEventListener('click', function() {
                    // Obtener el valor de la celda (ID de ingresoFK)
                    var idIngresoFK = cell.textContent;
                    // Realizar solicitud AJAX al servidor
                    var xhr = new XMLHttpRequest();
                    xhr.onreadystatechange = function() {
                        if (this.readyState == 4 && this.status == 200) {
                            // Actualizar el contenido del div con la respuesta del servidor
                            document.getElementById("tablaInventario").innerHTML = this.responseText;
                        }
                    };
                    xhr.open("GET", "obtener_inventario.php?id_ingresoFK=" + idIngresoFK, true);
                    xhr.send();
                });
            });
            // Ahora que hemos agregado los eventos de clic, inicializamos DataTables
            $(tablaIngresos).DataTable();
        }
    };
    xhr.open("GET", "mostrar_ingresos.php", true); // Archivo PHP que genera la tabla de ingresos
    xhr.send();
}
