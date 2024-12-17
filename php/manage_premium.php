<?php
session_start();
include_once "config.php";

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['action']) && isset($data['user_id'])) {
    $user_id = mysqli_real_escape_string($conn, $data['user_id']);

    if ($data['action'] == 'buy') {
        // Activar premium
        $query = "UPDATE users SET is_premium = 1 WHERE unique_id = {$user_id}";
    } elseif ($data['action'] == 'cancel') {
        // Desactivar premium
        $query = "UPDATE users SET is_premium = 0 WHERE unique_id = {$user_id}";
    } else {
        echo json_encode(['success' => false]);
        exit();
    }

    if (mysqli_query($conn, $query)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
} else {
    echo json_encode(['success' => false]);
}
?>
