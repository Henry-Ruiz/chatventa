<?php
session_start();
include_once "config.php";

if (!isset($_SESSION['unique_id'])) {
    header("location: ../login.php");
    exit();
}

$user_id = mysqli_real_escape_string($conn, $_GET['user_id']);

// Modifica la consulta para usar `is_premium`
$sql = mysqli_query($conn, "SELECT fname, lname, email, is_premium FROM users WHERE unique_id = {$user_id}");

// Verificar si la consulta fue exitosa
if (!$sql) {
    die("Error en la consulta SQL: " . mysqli_error($conn));
}

if (mysqli_num_rows($sql) > 0) {
    $user = mysqli_fetch_assoc($sql);
    $nombre = $user['fname'] . " " . $user['lname'];
    $email = $user['email'];
    $is_premium = $user['is_premium']; // Estado del plan premium
} else {
    echo "No se encontró la información del usuario.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Factura Premium</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 20px;
            border: 1px solid #ccc;
        }
        h1 {
            text-align: center;
            color: #4caf50;
        }
        p {
            font-size: 18px;
        }
    </style>
</head>
<body>
    <h1>Factura del Plan Premium</h1>
    <p><strong>Nombre:</strong> <?php echo $nombre; ?></p>
    <p><strong>Email:</strong> <?php echo $email; ?></p>
    <p><strong>Plan:</strong> <?php echo $is_premium ? "Premium" : "Básico"; ?></p>
    <p><strong>Precio:</strong> <?php echo $is_premium ? "$9.99" : "$0.00"; ?></p>
</body>
</html>
