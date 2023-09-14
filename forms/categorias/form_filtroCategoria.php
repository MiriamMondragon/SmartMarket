<?php
include("../menu/menu.php");
//Incluye el menu principal
include("../../php/conexion.php");
//Llama a la conexión a la base de datos mediante conexion.php
if ($_SESSION["admin"] == 1) {
?>
    <!DOCTYPE html>
    <html>

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <link href="../../recursos/bus.ico" rel="shortcut icon" type="image/x-icon">
        <title>Categorías Registradas - Smart Market</title>
        <!--Scripts-->
        <script type="text/javascript">
            function validar() {
                if (document.getElementById('txtNoCategoria').value == '' && document.getElementById('cmbEstado').value == '') {
                    alert('Por favor añada un filtro de búsqueda');
                } else {
                    document.getElementById("accion").value = "consultar"; //Al cambiar este valor, el incrutado PHP entra a la condicion
                    document.getElementById("formulario").submit(); //Al hacer submit PHP puede recuperar los valores POST necesarios abajo

                }
                return false;
            }
        </script>
        <!--Fin Scripts-->
    </head>

    <body>

        <div class="container">
            <div class="col-12 text-center mt-5 mb-5">
                <h3 style="background-color: #6AB759; color: white;">Filtrar Categorías</h3>
            </div>
            <form name='formulario' id='formulario' method='POST' action="">
                <input type="hidden" name="accion" id="accion" value="">
                <div class="col-12">
                    <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2 col-xl-2"></div>
                    <div class="row">
                        <!--Bootstrap: Divide en columnas la fila, de 12 columnas este div esta destinado a abarcar 4 columnas-->
                        <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2 col-xl-2"></div>
                        <!--Id Categoria-->
                        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
                            <div class="form-group mt-2 mb-2">
                                <label for="txtNoCategoria" class="form-label">No. Categoría</label>
                                <input type="text" class="form-control" name="txtNoCategoria" id="txtNoCategoria" placeholder="Ingrese el número de la categoría">
                            </div>
                        </div>
                        <!--Estado-->
                        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
                            <div class="form-group mt-2 mb-2">
                                <label for="cmbEstado" class="form-label">Estado</label>
                                <select class="form-control" name="cmbEstado" id="cmbEstado">
                                    <option value="">-- Seleccione un Estado --</option>
                                    <option value="1">Activa</option>
                                    <option value="0">Inactiva</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <!--Botones-->
                            <div class="d-flex justify-content-center">
                                <?php
                                $accion = isset($_POST["accion"]) ? $_POST["accion"] : ""; //La accion que cambia con la funcion validar()
                                if ($accion == "consultar") {
                                    echo "<a class='btn btn-secondary m-3' href='form_filtroCategoria.php'>Regresar</a>";
                                }
                                ?>
                                <button onClick="return validar()" name="btnBuscar" id="btnBuscar" class="btn btn-primary m-3" style="background-color: #6AB759; border-color: #6AB759;">Buscar</button>
                                <!--Utiliza el script especificado en el head para validar que el campo no este vacio y hacer submit-->
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="d-flex justify-content-center my-4">
            <!--Bootstrap: Centrado de div-->
            <table id='tabla' class="table table-striped table-bordered" style="max-width: 1100px;">
                <!--Bootstrap: Estilo de tabla-->
                <?php
                $accion = isset($_POST["accion"]) ? $_POST["accion"] : ""; //La accion que cambia con la funcion validar()
                $idCategoria = isset($_POST["txtNoCategoria"]) ? $_POST["txtNoCategoria"] : "";
                $estado = isset($_POST["cmbEstado"]) ? $_POST["cmbEstado"] : "";
                if ($accion == "consultar") {
                    $sql = '';
                    if ($idCategoria != '') {
                        $sql = "SELECT * FROM Categorias WHERE Id_Categoria = '$idCategoria'";
                    }
                    if ($estado != '') {
                        if ($sql == "") {
                            if ($estado == 1) {
                                $sql = "SELECT * FROM Categorias WHERE Activo = 1";
                            } else {
                                $sql = "SELECT * FROM Categorias WHERE Activo = 0";
                            }
                        } else {
                            if ($estado == 0) {
                                $sql = $sql . " AND Activo = 0";
                            } else {
                                $sql = $sql . " AND Activo = 1";
                            }
                        }
                    }
                    $sql = $sql . " ORDER BY Id_Categoria";
                    //Dependiendo del filtro que se haya llenado realizara la consulta
                    $result = mysqli_query($conexion, $sql);
                    if (mysqli_num_rows($result) != 0) { //Si la respuesta contiene por lo menos un registro, imprime la tabla
                        echo
                        "<thead>
                        <tr>
                            <th scope='col'>No. Categoria</th>
                            <th scope='col'>Nombre</th>
                            <th scope='col'>Descripción</th>
                            <th scope='col'>Estado</th>
                            <th style='text-align: center;' scope='col'>Actualizar</th>
                        </tr>
                        </thead>
                        <tbody>";

                        while ($row = mysqli_fetch_assoc($result)) {
                            if ($row["Activo"] == 0) {
                                $estadoCategoria = 'Inactiva';
                            } else {
                                $estadoCategoria = 'Activa';
                            }
                            echo
                            "<tr>
						<th scope='row'>" . $row["Id_Categoria"] . "</th>
						<td>" . $row["Categoria"] . "</td>
                        <td>" . $row["Descripcion"] . "</td>
                        <td>" . $estadoCategoria . "</td>
                        <td style='text-align: center'><a class='btn btn-primary' style='background-color: #6AB759; border-color: #6AB759;' href='form_actualizarCategoria.php?idCategoria=" . $row["Id_Categoria"] . "'>Actualizar</a></td>
					    </tr>"; //El enlace redirecciona al formulario de actualizar, y mediante metodo GET envia el Id del Registro
                        }
                    }
                    echo "</table>"; //Cerrado de tabla
                    if (mysqli_num_rows($result) == 0) { //Si la respuesta no contiene ningun registro, imprime que no hay resultados
                        echo
                        "<div class='col-12 text-center mt-5 mb-5'>
                        <p>No se encontraron resultados</p>
                    </div>";
                    }
                } else {
                    $sql = "SELECT * FROM Categorias";
                    //Impresion de todos los registros por defecto
                    $result = mysqli_query($conexion, $sql);
                    if (mysqli_num_rows($result) != 0) { //Si la respuesta contiene por lo menos un registro, imprime la tabla
                        echo
                        "<thead>
                        <tr>
                            <th scope='col'>No. Categoria</th>
                            <th scope='col'>Nombre</th>
                            <th scope='col'>Descripción</th>
                            <th scope='col'>Estado</th>
                            <th style='text-align: center;' scope='col'>Actualizar</th>
                        </tr>
                        </thead>
                        <tbody>";

                        while ($row = mysqli_fetch_assoc($result)) {
                            if ($row["Activo"] == 0) {
                                $estadoCategoria = 'Inactiva';
                            } else {
                                $estadoCategoria = 'Activa';
                            }
                            echo
                            "<tr>
						<th scope='row'>" . $row["Id_Categoria"] . "</th>
						<td>" . $row["Categoria"] . "</td>
                        <td>" . $row["Descripcion"] . "</td>
                        <td>" . $estadoCategoria . "</td>
                        <td style='text-align: center'><a class='btn btn-primary' style='background-color: #6AB759; border-color: #6AB759;' href='form_actualizarCategoria.php?idCategoria=" . $row["Id_Categoria"] . "'>Actualizar</a></td>
					    </tr>"; //El enlace redirecciona al formulario de actualizar, y mediante metodo GET envia el Id de la catategoria
                        }
                    }
                    echo "</table>"; //Cerrado de tabla
                }
                ?>
                </tbody>
            </table>
        </div>
        <br>

        <footer style="clear: both; position: relative; margin-top: 150px;">
            <div style="background-color: #24242c; width: 1481px; height: 58px;"></div>
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