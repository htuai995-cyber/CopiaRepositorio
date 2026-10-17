<?php
session_start();
require_once __DIR__ . '/../controller/AuthController.php';

$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = trim($_POST['correo'] ?? '');
    $clave = trim($_POST['clave'] ?? '');

    if ($correo === '' || $clave === '') {
        $mensaje = 'Complete todos los campos';
    } else {
        $auth = new AuthController();

        if ($auth->login($correo, $clave)) {
            header('Location: home.php');
            exit;
        }

        $mensaje = 'Correo o clave incorrectos';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="index,follow">
    <meta name="description" content="Sistema de cálculo del área y perímetro del paralelogramo">
    <meta name="keywords" content="paralelogramo, sistema, base de datos">
    <meta name="author" content="BC TECH">

    <link rel="icon" href="../imagenes/icono21.ico">
    <link rel="stylesheet" href="../assetes/css/style.css?v=3">

    <title>Paralelogramo</title>
</head>
<body>
    <main>
        <section class="caja" aria-label="Inicio de sesión">
            <form class="login-card" action="" method="post" id="frmLogin">
                <img class="login-ilustracion" src="../imagenes/login-matematicas.png" alt="Ilustración de fórmulas matemáticas">
                <div class="login-contenido">
                    <p class="login-etiqueta">BIENVENIDO</p>
                    <h1>Sistema Paralelogramo</h1>

                    <?php if ($mensaje !== '') : ?>
                        <p class="login-error" role="alert"><?php echo htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8'); ?></p>
                    <?php endif; ?>

                    <div class="login-campo">
                        <label for="correo">Correo electrónico</label>
                        <input type="email" name="correo" id="correo" placeholder="nombre@correo.com"
                            value="<?php echo htmlspecialchars($correo ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                            autocomplete="email" required>
                    </div>
                    <div class="login-campo">
                        <label for="clave">Contraseña</label>
                        <input type="password" name="clave" id="clave" placeholder="Ingresa tu contraseña"
                            autocomplete="current-password" required>
                    </div>
                    <button type="submit" id="btnIngresar">Ingresar</button>
                </div>
            </form>
        </section>
    </main>
</body>
</html>