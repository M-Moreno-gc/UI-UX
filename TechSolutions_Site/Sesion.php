<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "teamnewtdb";


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Conexion fallida: " . $conn->connect_error);
}


$usuario = null;
$mensaje = "";


$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
if ($conn->query($sql) === TRUE) {

    $conn->select_db($dbname);
    

    $ctabla = "CREATE TABLE IF NOT EXISTS usuarios (
        id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
        nombre VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL,
        telefono VARCHAR(20) NOT NULL,
        password VARCHAR(255) NOT NULL
    )";
    
    if ($conn->query($ctabla) === TRUE) {
    } else {
        echo "No se creo tabla: " . $conn->error;
    }
} else {
    echo "No se creo BD: " . $conn->error;
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $id_buscar = $_POST['id'];
    

    $sql = "SELECT * FROM usuarios WHERE id = ?";
    $rel = $conn->prepare($sql);
    $rel->bind_param("i", $id_buscar);
    $rel->execute();
    $result = $rel->get_result();
    
    if ($result->num_rows > 0) {
        $usuario = $result->fetch_assoc();
   
        header("Location: Sesion.php?id=" . $id_buscar);
        exit();
    } else {
        $mensaje = "usuario no encontrado";
    }
    $rel->close();
}


if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id_usuario = $_GET['id'];
    
    $sql = "SELECT * FROM usuarios WHERE id = ?";
    $rel = $conn->prepare($sql);
    $rel->bind_param("i", $id_usuario);
    $rel->execute();
    $result = $rel->get_result();
    
    if ($result->num_rows > 0) {
        $usuario = $result->fetch_assoc();
    }
    $rel->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['nombre']) && isset($_GET['id'])) {
    $id = $_GET['id'];
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $telefono = $_POST['tel'];
    $password = $_POST['password'];
    
    $sql = "UPDATE usuarios SET nombre = ?, email = ?, telefono = ?, password = ? WHERE id = ?";
    $rel = $conn->prepare($sql);
    $rel->bind_param("ssssi", $nombre, $email, $telefono, $password, $id);
    
    if ($rel->execute()) {
        $mensaje = "usuario actualizado correctamente";
        $usuario['nombre'] = $nombre;
        $usuario['email'] = $email;
        $usuario['telefono'] = $telefono;
        $usuario['password'] = $password;
    } else {
        $mensaje = "Error al actualizar: " . $conn->error;
    }
    $rel->close();
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['nombre_registro'])) {
    $nombre = $_POST['nombre_registro'];
    $email = $_POST['email_registro'];
    $telefono = $_POST['tel_registro'];
    $password = $_POST['password_registro'];
    

    if (strlen($telefono) < 10) {
        $mensaje = "El telefono debe tener al menos 10 digitos";
    } elseif (strlen($password) < 6) {
        $mensaje = "La contrase;a debe tener al menos 6 caracteres";
    } else {

        $sql = "INSERT INTO usuarios (nombre, email, telefono, password) VALUES (?, ?, ?, ?)";
        $rel = $conn->prepare($sql);
        $rel->bind_param("ssss", $nombre, $email, $telefono, $password);
        
        if ($rel->execute()) {
            $mensaje = "Usuario registrado exitosamente";
            echo "<script>
                document.getElementById('nombre_registro').value = '';
                document.getElementById('email_registro').value = '';
                document.getElementById('tel_registro').value = '';
                document.getElementById('password_registro').value = '';
            </script>";
        } else {
            $mensaje = "Error al registrar: " . $conn->error;
        }
        $rel->close();
    }
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['borrar']) && isset($_GET['id'])) {
    $id = $_GET['id'];
    
    $sql = "DELETE FROM usuarios WHERE id = ?";
    $rel = $conn->prepare($sql);
    $rel->bind_param("i", $id);
    
    if ($rel->execute()) {
        $mensaje = "Usuario eliminado correctamente";
        $usuario = null;
        echo "<script>window.location.href = 'Sesion.php';</script>";
    } else {
        $mensaje = "Error al eliminar: " . $conn->error;
    }
    $rel->close();
}


$conn->close();
?>

<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Team Newt - Inicia Sesion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="./src/SesionCss.css">
</head>
<body>

    <!-- Nav -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light sticky-top shadow-sm">
        <div class="container" id="redi">
            <a href="" class="navbar-brand d-flex align-items-center" onclick="window.location.href='./index.html';">
                <img src="./src/img/Logo.png" alt="" class="img-fluid d-inline-block me-2" width="50" height="50">
                <h2 class="fw-bold d-inline-block mb-0">Team Newt</h2>
            </a>
            <div class="navbar-nav ms-auto">
                <a href="index.html" class="nav-link fw-semibold" onclick="window.location.href='./index.html';">Volver a Inicio</a>
            </div>
        </div>
    </nav>

    <!-- ensajes -->
    <?php if (!empty($mensaje)): ?>
        <div class="alert alert-info alert-dismissible fade show mx-3 mt-3" role="alert">
            <?php echo $mensaje; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="log-contr">
        <div class="log-card">
            <!-- Pesta;as -->
            <ul class="nav nav-bt nav-justified" id="authTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="login-tab" data-bs-toggle="pill" data-bs-target="#login" type="button" role="tab">
                        <i class="bi bi-eye"></i> Usuarios
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="register-tab" data-bs-toggle="pill" data-bs-target="#register" type="button" role="tab">
                        <i class="bi bi-person-plus me-2"></i>Registrarse
                    </button>
                </li>
            </ul>

            <div class="tab-cs" id="authTabContent">
                <div class="tab-pane fade show active" id="login" role="tabpanel">

                    <!-- ID-->
                    <form class="buscarid d-flex mb-3" method="post" action="Sesion.php">
                        <input class="form-control me-2" type="search" name="id" placeholder="Buscar por ID" aria-label="Buscar" required>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search me-1"></i>Buscar
                        </button>
                    </form>

                    <!-- LOGIN-->
                    <?php if(isset($usuario)): ?>
                    <form method="POST" action="Sesion.php?id=<?php echo $_GET['id'] ?? ''; ?>">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre Completo</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo htmlspecialchars($usuario['nombre'] ?? ''); ?>">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($usuario['email'] ?? ''); ?>">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="tel" class="form-label">Telefono</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                                <input type="text" class="form-control" id="tel" name="tel" value="<?php echo htmlspecialchars($usuario['telefono'] ?? ''); ?>">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Contrase;a</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                <input type="text" class="form-control" id="password" name="password" value="<?php echo htmlspecialchars($usuario['password'] ?? ''); ?>">
                            </div>
                        </div>

                        <div id="actBtn" class="mb-3">
                            <button type="submit" class="btn btn-primary w-100 mb-2" name="actualizar">
                                <i class="bi bi-check-circle me-2"></i>Actualizar Usuario
                            </button>
                            <button type="submit" class="btn btn-danger w-100 mb-2" name="borrar" onclick="return confirm('¿Estas seguro de eliminar este usuario?')">
                                <i class="bi bi-trash me-2"></i>Eliminar Usuario
                            </button>
                        </div>
                    </form>
                    <?php endif; ?>
                </div>

                <!-- REG -->
                <div class="tab-pane fade" id="register" role="tabpanel">
                    <form method="POST" action="Sesion.php">
                        <div class="mb-3">
                            <label for="nombre_registro" class="form-label">Nombre Completo</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                <input type="text" class="form-control" id="nombre_registro" name="nombre_registro" required placeholder="Nombre">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="email_registro" class="form-label">Email</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <input type="email" class="form-control" id="email_registro" name="email_registro" required placeholder="Correo">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="tel_registro" class="form-label">Telefono</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                                <input type="text" class="form-control" id="tel_registro" name="tel_registro" required placeholder="+52" minlength="10">
                            </div>
                            <small class="text-muted">Minimo 10 digitos</small>
                        </div>
                        <div class="mb-3">
                            <label for="password_registro" class="form-label">Contrase;a</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                <input type="password" class="form-control" id="password_registro" name="password_registro" required minlength="6">
                            </div>
                            <small class="text-muted">Minimo 6 caracteres</small>
                        </div>
                        <button type="submit" name="register" class="btn btn-primary w-100">
                            <i class="bi bi-person-plus me-2"></i>Registrarse
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    
    <script>

        document.addEventListener('DOMContentLoaded', function() {
            <?php if(isset($_POST['register'])): ?>
                var registerTab = new bootstrap.Tab(document.getElementById('register-tab'));
                registerTab.show();
            <?php endif; ?>
        });

    </script>
</body>
</html>