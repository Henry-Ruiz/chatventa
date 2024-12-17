<?php
session_start();
include_once "config.php";

if (!isset($_SESSION['unique_id'])) {
    echo json_encode(['success' => false, 'message' => 'No autorizado']);
    exit();
}

if (isset($_FILES['image'])) {
    $incoming_id = mysqli_real_escape_string($conn, $_POST['incoming_id']);
    $outgoing_id = $_SESSION['unique_id'];

    $file_name = $_FILES['image']['name'];
    $file_tmp = $_FILES['image']['tmp_name'];
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];

    if (in_array($file_ext, $allowed_extensions)) {
        $new_name = time() . '_' . $file_name;
        $upload_path = "uploads/" . $new_name;

        if (move_uploaded_file($file_tmp, $upload_path)) {
            $sql = "INSERT INTO messages (incoming_id, outgoing_id, message, type) 
                    VALUES ({$incoming_id}, {$outgoing_id}, '{$new_name}', 'image')";
            $query = mysqli_query($conn, $sql);
            if ($query) {
                echo json_encode(['success' => true]);
                exit();
            }
        }
    }
}

echo json_encode(['success' => false, 'message' => 'Error al subir la imagen']);
