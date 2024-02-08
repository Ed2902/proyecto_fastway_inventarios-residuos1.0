function obtenerSiguienteIDProducto() {
    fetch('../objetos_guardar/producto.php')
    .then(response => response.text())
    .then(data => {
        document.getElementById('id_producto').value = data;
    })
    .catch(error => console.error('Error al obtener el siguiente ID de producto:', error));
}
