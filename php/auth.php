<?php
// Conexion a la base de datos
$conn = new mysqli("localhost", "root", "", "mi_base_datos");

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Verifica si es registro o inicio de sesión
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $action = $_POST["action"];

    if ($action === "register") {
        // Registro de nuevo usuario
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO usuarios (username, password) VALUES ('$username', '$hashedPassword')";
        
        if ($conn->query($sql) === TRUE) {
            echo "Registro exitoso";
        } else {
            echo "Error al registrar: " . $conn->error;
        }

    } elseif ($action === "login") {
        // Inicio de sesión
        $sql = "SELECT password FROM usuarios WHERE username = '$username'";
        $result = $conn->query($sql);
        
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['password'])) {
                echo "Inicio de sesión exitoso";
            } else {
                echo "Contraseña incorrecta";
            }
        } else {
            echo "Usuario no encontrado";
        }
    }
}

$conn->close();
?>
