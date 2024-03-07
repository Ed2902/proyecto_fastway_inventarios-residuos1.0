    <?php    
        require_once('../clases/producto.php');
        require_once('../clases/producto.php');

        $ultimoIdDisponible = Producto::obtenerUltimoIdDisponible();
    ?>
    
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Producto</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Ingreso de Inventario</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        <link rel="stylesheet" href="../css/styles.css">
    </head>
    <body>
        <div class="container-fluid">
            <div class="row">
                <div class="col-1 d-none d-sm-block">
                <!-- Icono de flecha hacia atrás (visible solo en pantallas mayores a 'sm') -->
                <a href="javascript:history.back()" class="btn-link i.fasbtn btn-link mt-2 ml-2"><i class="fas fa-arrow-left" style="color: red;"></i></a>
                </div>
                <div class="col-11">
                    <div class="row justify-content-center">
                        <div class="col-md-9">
                            <form action="../objetos_guardar/producto.php"  method="post"  id="Producto" class="text-center border border-light p-3 shadow-lg rounded-lg" style="border-radius: 18px; margin-top: 25px;">
                                <p class="h2 mb-4">Ingreso de productos</p>

                                <div class="row mb-4">
                                <div class="col-md-6">
                                        <label for="id_producto" class="form-label">ID Producto</label>
                                        <input type="text" class="form-control" id="id_producto" name="id_producto" value="<?php echo isset($idProductoGuardado) ? $idProductoGuardado : $ultimoIdDisponible; ?>" readonly>
                                </div>                                  
                                    <div class="col-md-6">
                                        <label for="nombre" class="form-label">Nombre</label>
                                        <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre">
                                    </div>
                                </div>
                                <div class="row mb-4 justify-content-center" >
                                    <div class="col-md-6">
                                        <label for="referencia" class="form-label">Referencia</label>
                                        <input type="text" class="form-control" id="nombre" name="referencia" placeholder="referencia">
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <label for="Diseno" class="form-label">Diseño</label>
                                        <input type="text" class="form-control" id="tipo" name="tipo" placeholder="Diseño">
                                    </div>

                                    <div class="col-md-6">
                                        <label for="clienteFK" class="form-label">Cliente</label>
                                        <input type="text" class="form-control" id="clienteFK" name="clienteFK" placeholder="Cliente">
                                    </div>
                                </div>
                                
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <label for="ancho" class="form-label">Ancho (cm)</label>
                                        <input type="text" class="form-control" id="ancho" name="ancho" placeholder="Ancho (cm)">
                                    </div>

                                    <div class="col-md-6">
                                        <label for="Alto" class="form-label">Alto (cm)</label>
                                        <input type="text" class="form-control" id="Alto" name="alto" placeholder="Alto (cm)">
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <label for="Profundo" class="form-label">Profundo (cm)</label>
                                        <input type="text" class="form-control" id="rofundo" name="profundo" placeholder="Profundo (cm)">
                                    </div>

                                    <div class="col-md-6">
                                        <label for="Usuario" class="form-label">Usuario Que da Ingreso</label>
                                        <input type="text" class="form-control" id="Usuario" name="id_usuarioFK" placeholder="Usuario Que da Ingreso">
                                    </div>
                                </div>
                                
                                <button type="submit" onclick="validarFormulario()" class="boton_agregar btn btn-info btn-lg">Agregar</button>
                                <button type="button" onclick="limpiarFormulario()" class="boton_cancelar btn btn-secondary btn-lg">Cancelar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
        <script src="../js/agregarproducto.js"></script>
        <script src="path/to/jquery.min.js"></script>
        <script src="path/to/bootstrap.bundle.min.js"></script>
    </body>
    </html>