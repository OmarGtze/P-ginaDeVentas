<?php
// index_procesar.php

session_start();
require "conecta.php";
$con = conecta();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Verificar si el usuario existe y está activo en la base de datos
    $sql = "SELECT * FROM clientes WHERE correo = ? AND pass = ? AND status = 1 AND eliminado = 0";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Usuario autenticado correctamente
        $usuario = $result->fetch_assoc();

        // Asignar variables de sesión
        $_SESSION['idUser'] = $usuario['id'];
        $_SESSION['nombreUser'] = $usuario['nombre'];
        $_SESSION['correoUser'] = $usuario['correo'];

        echo 'success';
    } else {
        // Usuario o contraseña incorrectos
        echo 'error';
    }
} else {
    // Redirigir si se accede directamente a este archivo sin un envío de formulario válido
    header("Location: index.php");
    exit();
}
?>
