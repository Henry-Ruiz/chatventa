<?php
session_start();
include_once "config.php";

if (!isset($_SESSION['unique_id'])) {
    echo json_encode(['success' => false, 'message' => 'Usuario no autenticado']);
    exit();
}

// Obtener detalles del usuario
$user_id = $_SESSION['unique_id'];
$sql = mysqli_query($conn, "SELECT fname, lname, email FROM users WHERE unique_id = {$user_id}");
if (mysqli_num_rows($sql) > 0) {
    $user = mysqli_fetch_assoc($sql);

    // Datos de la factura
    $invoice_number = "INV-" . strtoupper(uniqid());
    $date = date("Y-m-d H:i:s");
    $amount = "9.99"; // Monto fijo para Premium, cámbialo si es necesario

    // Crear la factura en HTML
    $invoice_content = "
    <html>
    <head>
        <title>Factura $invoice_number</title>
        <style>
            body { font-family: Arial, sans-serif; }
            .container { width: 80%; margin: 0 auto; padding: 20px; border: 1px solid #ddd; }
            .header { text-align: center; margin-bottom: 20px; }
            .details { margin-top: 20px; }
            .footer { text-align: center; margin-top: 30px; font-size: 12px; color: #555; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h1>Factura</h1>
                <p>Número: $invoice_number</p>
                <p>Fecha: $date</p>
            </div>
            <div class='details'>
                <p><strong>Nombre:</strong> {$user['fname']} {$user['lname']}</p>
                <p><strong>Email:</strong> {$user['email']}</p>
                <p><strong>Monto:</strong> $ $amount</p>
                <p><strong>Descripción:</strong> Suscripción Premium</p>
            </div>
            <div class='footer'>
                <p>Gracias por tu compra.</p>
            </div>
        </div>
    </body>
    </html>";

    // Guardar la factura en un archivo
    $invoice_path = "../invoices/{$invoice_number}.html";
    if (!file_exists('../invoices')) {
        mkdir('../invoices', 0777, true);
    }
    file_put_contents($invoice_path, $invoice_content);

    // Responder con el enlace a la factura
    echo json_encode(['success' => true, 'invoice_url' => $invoice_path]);
} else {
    echo json_encode(['success' => false, 'message' => 'Usuario no encontrado']);
}
?>
