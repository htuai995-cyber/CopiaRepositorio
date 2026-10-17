<?php
session_start();
require_once __DIR__ . '/../controller/AuthController.php';
require_once __DIR__ . '/../controller/ParalelogramoController.php';

AuthController::verificarSesion();

$paralelogramoController = new ParalelogramoController();
$mensaje = $_GET['mensaje'] ?? '';
$mensajeFormulario = $paralelogramoController->procesarFormulario();

if ($mensajeFormulario !== '') {
    $mensaje = $mensajeFormulario;
}

$buscar = trim($_GET['buscar'] ?? '');
$registroEditar = null;

if (isset($_GET['editar'])) {
    $registroEditar = $paralelogramoController->obtenerParaEditar((int) $_GET['editar']);
}

$registros = $paralelogramoController->listar($buscar);

$idActual = $registroEditar['id_paralelogramo'] ?? '';
$baseActual = $registroEditar['base'] ?? '';
$ladoActual = $registroEditar['lado'] ?? '';
$alturaActual = $registroEditar['altura'] ?? '';
$detalleActual = $registroEditar['detalle'] ?? '';
$areaActual = $registroEditar['area'] ?? '';
$perimetroActual = $registroEditar['perimetro'] ?? '';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="index,follow">
    <meta name="description" content="Calculo del area y perimetro del paralelogramo">
    <meta name="keywords" content="paralelogramo, sistema, base de datos">
    <meta name="author" content="BC TECH">

    <link rel="icon" href="../imagenes/icono21.ico">
    <link rel="stylesheet" href="../assetes/css/style_home.css?v=4">

    <title>Paralelogramo - Principal</title>
</head>
<body>
    <header class="headeerHome">
        <div class="lineaSup">
            <div class="logo-links">
                <div class="logo">
                    <img src="../imagenes/logo.png" alt="">
                    <h1>SISTEMA PARALELOGRAMO</h1>
                </div>               
                <a href="">Quienes somos</a>
            </div>
            <div class="buscarHeader">
                <form class="form1" action="home.php" method="get" role="search">
                    <label class="visualmenteOculto" for="buscarHeader">Buscar en el historial</label>
                    <input type="search" name="buscar" id="buscarHeader" placeholder="Buscar registros..."
                    value="<?php echo htmlspecialchars($buscar); ?>">
                    <button type="submit">Buscar</button>
                </form>
            </div>
        </div>
        <div class="lineaInf">
            <nav class="menu" aria-label="Navegación principal">
                <ul>
                    <li><a href="home.php" aria-current="page">Inicio</a></li>
                    <li><a href="#">Nuestra historia</a></li>
                    <li><a href="logout.php" onclick="return confirm('¿Estás seguro de que deseas cerrar sesión? Tendrás que volver a ingresar para acceder al sistema.');">Cerrar sesión</a></li>
                </ul>
            </nav>
        </div>
    </header>

    
    <main>
        <div class="datos">
            <div class="titulo">
                <h1>Calculo del area y perimetro de un Paralelogramo</h1>
                <p>Usuario: <?php echo htmlspecialchars($_SESSION['usuario']); ?></p>
            </div>

            <?php if ($mensaje !== '') : ?>
                <p class="mensaje">
                    <?php echo htmlspecialchars(ucfirst($mensaje)); ?>
                </p>
            <?php endif; ?>


            <form action="" method="post" class="form2" id="frmFormulario">
                <input type="hidden" name="id_paralelogramo" value="<?php echo htmlspecialchars($idActual); ?>">

                <div class="inputs">
                    <label for="txtBase">Base</label>
                    <input class="inputHome" type="number" min="0" step="any" name="txtBase" id="txtBase" placeholder="Base"
                     value="<?php echo htmlspecialchars($baseActual); ?>">
                </div>
                <div class="inputs">
                    <label for="txtLado">Lado</label>
                    <input class="inputHome" type="number" min="0" step="any" name="txtLado" id="txtLado" placeholder="Lado"
                     value="<?php echo htmlspecialchars($ladoActual); ?>">
                </div>
                <div class="inputs">
                    <label for="txtAltura">Altura</label>
                    <input class="inputHome" type="number" min="0" step="any" name="txtAltura" id="txtAltura" placeholder="Altura"
                     value="<?php echo htmlspecialchars($alturaActual); ?>">
                </div>
                <div class="inputs">
                    <label for="comentario">Comentario</label>
                    <input class="inputHome" type="text" placeholder="Opcional: Un comentario"
                    name="txtDetalle" id="txtDetalle" value="<?php echo htmlspecialchars($detalleActual); ?>">
                </div>
                <div class="inputs">
                    <label for="txtArea">Área</label>
                    <input class="inputHome" type="text" name="txtArea" id="txtArea" readonly placeholder="Área"
                     value="<?php echo htmlspecialchars($areaActual); ?>">
                </div>
                <div class="inputs">
                    <label for="txtPerimetro">Perímetro</label>
                    <input class="inputHome" type="text" name="txtPerimetro" id="txtPerimetro" readonly placeholder="Perímetro"
                     value="<?php echo htmlspecialchars($perimetroActual); ?>">
                </div>
                <div class="botones">
                    <button type="button" onclick="calcularParalelogramo();">Calcular</button>
                    <a class="btn-link cancelar" href="home.php">Cancelar</a>
                    <button type="submit" name="accion" value="guardar">
                        <?php echo $idActual !== '' ? 'Actualizar' : 'Guardar'; ?>
                    </button>
                </div>
            </form>
        </div>
        <div class="buscar">
            <form action="home.php" method="get" id="frmBuscar" role="search">
                <label class="visualmenteOculto" for="buscarHistorial">Buscar en el historial</label>
                <button type="submit">Buscar</button>
                <input type="search" name="buscar" id="buscarHistorial" placeholder="Buscar por medida, detalle, ID o fecha"
                 value="<?php echo htmlspecialchars($buscar); ?>">
                <?php if ($buscar !== '') : ?>
                    <a class="btn-link limpiar" href="home.php">Limpiar</a>
                <?php endif; ?>
            </form>
        </div>

        <div class="tabla">
            <h1>Historial de los calculos</h1>
            <table>
                <thead>
                    <tr>
                        <th>id</th>
                        <th>Base</th>
                        <th>Lado</th>
                        <th>Altura</th>
                        <th>Detalle</th>
                        <th>Area</th>
                        <th>Perimetro</th>
                        <th>Fecha y hora</th>
                        <th>Botones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($registros) > 0) : ?>
                        <?php foreach ($registros as $fila) : ?>
                            <tr>
                                <td><?php echo htmlspecialchars($fila['id_paralelogramo']); ?></td>
                                <td><?php echo htmlspecialchars($fila['base']); ?></td>
                                <td><?php echo htmlspecialchars($fila['lado']); ?></td>
                                <td><?php echo htmlspecialchars($fila['altura']); ?></td>
                                <td><?php echo htmlspecialchars($fila['detalle']); ?></td>
                                <td><?php echo htmlspecialchars($fila['area']); ?></td>
                                <td><?php echo htmlspecialchars($fila['perimetro']); ?></td>
                                <td><?php echo htmlspecialchars($fila['fecha_registro']); ?></td>
                                <td class="acciones">
                                    <a class="btn-tabla editar" href="home.php?editar=<?php echo (int) $fila['id_paralelogramo']; ?>">Editar</a>

                                    <form action="" method="post" onsubmit="return confirm('¿Desea eliminar este registro?');">
                                        <input type="hidden" name="id_paralelogramo" value="<?php echo (int) $fila['id_paralelogramo']; ?>">
                                        <button class="btn-tabla eliminar" type="submit" name="accion" value="eliminar">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="9">No hay registros para mostrar.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            
        </div>
    </main>

    <script src="../assetes/js/app.js?v=2"></script>
</body>
</html>