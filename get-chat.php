<?php
// Conexión a la base de datos
include_once "config.php";

// Obtener el ID del usuario con el que estás chateando
$incoming_id = mysqli_real_escape_string($conn, $_POST['incoming_id']);
$outgoing_id = $_SESSION['unique_id']; // El usuario actual

// Obtener los mensajes entre los usuarios
$sql = mysqli_query($conn, "SELECT * FROM messages WHERE (incoming_id = {$incoming_id} AND outgoing_id = {$outgoing_id}) OR (incoming_id = {$outgoing_id} AND outgoing_id = {$incoming_id}) ORDER BY msg_id DESC");

// Comenzar a mostrar los mensajes
while ($row = mysqli_fetch_assoc($sql)) {
    $image = $row['image']; // El nombre de la imagen
    $message = $row['message']; // El mensaje de texto

    // Si hay una imagen, mostrarla
    if ($image) {
        echo "<div class='chat-message'>
                <img src='php/images/$image' alt='Imagen' class='chat-image'>
                <p>$message</p>
              </div>";
    } else {
        echo "<div class='chat-message'><p>$message</p></div>";
    }
}
?>
