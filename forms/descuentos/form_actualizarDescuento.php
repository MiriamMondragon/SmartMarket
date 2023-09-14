<?php
include("../menu/menu.php");
include("../../php/conexion.php");
//Llama a la conexión a la base de datos mediante conexion.php
include("../../php/descuentos/recuperarDescuento.php");
//Incluye el archivo php que recupera los datos de la BD mediante una vista para utilizar esos valores en los campos
include("../../php/descuentos/actualizarDescuento.php");
//Incluye el archivo php que actualiza o desactiva descuento en la BD cuando se cambia el campo de "accion" al valor de guardar o desactivar
if ($_SESSION["admin"] == 1) {
?>

    <!DOCTYPE html>
    <html>

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Actualizar Descuento - Smart Market</title>
        <script src="../../js/jquery-1.10.2.min.js"></script>
        <!--Libreria básica de JQuery-->
        <script src="../../js/descuentos/descuentos.js"></script>
    </head>

    <body>
        <!--Tanto los valores de Bootstrap como los de las variables escondidas se explican en el form_insertarDescuento.php-->
        <div class="container">
            <div class="col-12 text-center mt-5 mb-5">
                <h3 style="background-color: #6AB759; color: white;">Actualizar Descuento</h3>
            </div>
            <form name='formulario' id='formulario' method='POST' action="">
                <input type="hidden" name="accion" id="accion" value="">
                <input type="hidden" name="usuarioLogin" id="usuarioLogin" <?php echo "value='" . $_SESSION["idUsuario"] . "'" ?>>
                <div class="col-12">
                    <div class="row">
                        <!--Identidad-->
                        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
                            <div class="form-group mt-2 mb-2">
                                <label for="txtIdDescuento" class="form-label">Id. del Descuento</label>
                                <input type="text" class="form-control" name="txtIdDescuento" id="txtIdDescuento" maxlength="15" placeholder="Ingrese el ID del Descuento" <?php echo 'value=' . $_GET['idDescuento'] ?> readonly>
                                <!--Un campo de solo lectura que tiene como valor la ID traida del metodo GET-->
                            </div>
                        </div>
                        <!--Nombres-->
                        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
                            <div class="form-group mt-2 mb-2">
                                <label for="txtNombre" class="form-label">Nombre del Descuento</label>
                                <input type="text" class="form-control" name="txtNombre" id="txtNombre" maxlength="45" placeholder="Ingrese el nombre del descuento" <?php echo "value='$nombre'" ?>>
                                <!--Campo que utiliza la variable de nombre de recuperarDescuento.php-->
                            </div>
                        </div>
                        <!--Valor-->
                        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
                            <div class="form-group mt-2 mb-2">
                                <label for="txtValor" class="form-label">Valor del Descuento</label>
                                <input type="text" class="form-control" name="txtValor" id="txtValor" maxlength="45" placeholder="Ingrese el valor del descuento" <?php echo "value='$valor'" ?>>
                                <!--Campo que utiliza la variable de valor de recuperarDescuento.php-->
                            </div>
                        </div>
                        <div class="row">
                            <!--Botones-->
                            <div class="d-flex justify-content-center">
                                <?php
                                if ($valor != 0) { //Si el producto está como descontinuado en la BD el boton será visible, si no, no
                                    echo "<button onClick='return desactivar()' name='btnDesactivar' id='btnDesactivar' class='btn btn-secondary m-5' style='background-color: #F08946; border-color: #F08946;'>Desactivar</button>";
                                    //Llama a la funcion desactivar() de descuentos.js
                                }
                                ?>
                                <button name="btnGuardar" id="btnGuardar" class="btn btn-primary m-5" style="background-color: #6AB759; border-color: #6AB759;" onClick="return validar()">Actualizar</button>
                                <!--El boton no llama a ninguna funcion directamente, desde este ultimo se invoca a validar() de descuentos.js donde se hace el respectivo submit y redireccionamiento al buscador o filtro-->
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <footer style="position: absolute; bottom: 0;">
            <div style="background-color: #24242c; width: 1496px; height: 58px;"></div>
        </footer>
    </body>

    </html>
<?php
} else { //Pagina que se carga cuando se trata de acceder con la url sin ser administrador
    echo "<script>
            window.location.href = '../../forms/principal/principal.php';
        </script>";
}
?>