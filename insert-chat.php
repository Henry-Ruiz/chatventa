<?php
session_start();
include_once "config.php";

if (!isset($_SESSION['unique_id'])) {
    exit();
}

$incoming_id = mysqli_real_escape_string($conn, $_POST['incoming_id']);
$outgoing_id = $_SESSION['unique_id'];
$message = mysqli_real_escape_string($conn, $_POST['message']);
$image = null;

// Verifica si hay una imagen adjunta
if (isset($_FILES['image'])) {
    $image_name = $_FILES['image']['name'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_size = $_FILES['image']['size'];
    $image_error = $_FILES['image']['error'];

    if ($image_error === 0) {
        $image_ext = pathinfo($image_name, PATHINFO_EXTENSION);
        $allowed_exts = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array(strtolower($image_ext), $allowed_exts)) {
            if ($image_size <= 5000000) { // Limitar el tamaño del archivo (5MB por ejemplo)
                $new_image_name = uniqid('', true) . "." . $image_ext;
                $image_folder = "images/" . $new_image_name;
                move_uploaded_file($image_tmp_name, $image_folder);
                $image = $new_image_name;
            }
        }
    }}

// Si el mensaje está vacío y no hay imagen, salimos
if (empty($message) && !$image) {
    exit();
}

// Inserta el mensaje o la imagen en la base de datos
$query = "INSERT INTO chats (incoming_id, outgoing_id, message, image) 
          VALUES ({$incoming_id}, {$outgoing_id}, '{$message}', '{$image}')";
mysqli_query($conn, $query);
?>
