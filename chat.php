<?php
session_start();
include_once "php/config.php";

if (!isset($_SESSION['unique_id'])) {
    header("location: login.php");
    exit();
}

$user_id = mysqli_real_escape_string($conn, $_GET['user_id']);
$sql = mysqli_query($conn, "SELECT * FROM users WHERE unique_id = {$user_id}");
if (mysqli_num_rows($sql) > 0) {
    $row = mysqli_fetch_assoc($sql);
} else {
    header("location: users.php");
    exit();
}

// Obtener el estado premium del usuario actual
$user_query = mysqli_query($conn, "SELECT is_premium FROM users WHERE unique_id = {$_SESSION['unique_id']}");
if (mysqli_num_rows($user_query) > 0) {
    $user_info = mysqli_fetch_assoc($user_query);
    $is_premium = $user_info['is_premium'];
} else {
    $is_premium = 0;
}
?>
<?php include_once "header.php"; ?>

<body>
  <div class="wrapper">
    <section class="chat-area">
      <header>
        <a href="users.php" class="back-icon"><i class="fas fa-arrow-left"></i></a>
        <img src="php/images/<?php echo $row['img']; ?>" alt="">
        <div class="details">
          <span><?php echo $row['fname'] . " " . $row['lname'] ?></span>
          <p><?php echo $row['status']; ?></p>
        </div>
        <div class="premium-status">
          <p><?php echo $is_premium ? 'Eres usuario premium' : 'No eres usuario premium'; ?></p>
          <?php if (!$is_premium): ?>
            <button id="buy-premium" class="premium-button" onclick="purchasePremium()">Comprar Premium</button>
          <?php else: ?>
            <button id="cancel-premium" class="premium-button" onclick="cancelPremium()">Desactivar Premium</button>
          <?php endif; ?>
        </div>
      </header>
      <div class="chat-box">
        <!-- Mensajes cargados dinámicamente -->
      </div>
      <form action="#" class="typing-area">
        <input type="text" class="incoming_id" name="incoming_id" value="<?php echo $user_id; ?>" hidden>
        <input type="text" name="message" class="input-field" placeholder="Escribe tu mensaje aquí..." autocomplete="off">
        <button><i class="fab fa-telegram-plane"></i></button>
        <?php if ($is_premium): ?>
          <label for="image-upload" class="tool-button">
            Enviar Imágenes
          </label>
          <input type="file" id="image-upload" style="display: none;" accept="image/*">
        <?php endif; ?>
      </form>
    </section>
  </div>

  <script>
    // Manejo de compra de premium
    function purchasePremium() {
        fetch('php/manage_premium.php', {
            method: 'POST',
            body: JSON.stringify({ action: 'buy', user_id: <?php echo $_SESSION['unique_id']; ?> }),
            headers: { 'Content-Type': 'application/json' }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('¡Felicidades! Ahora eres usuario premium.');
                location.reload();
            } else {
                alert('Hubo un problema al procesar tu compra.');
            }
        });
    }

    // Manejo de cancelación de premium
    function cancelPremium() {
        fetch('php/manage_premium.php', {
            method: 'POST',
            body: JSON.stringify({ action: 'cancel', user_id: <?php echo $_SESSION['unique_id']; ?> }),
            headers: { 'Content-Type': 'application/json' }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Tu cuenta ya no es premium.');
                location.reload();
            } else {
                alert('Hubo un problema al procesar tu solicitud.');
            }
        });
    }
  </script>

  <script src="javascript/chat.js"></script>

  <style>
    /* Botones */
    .premium-button {
        background-color: #ff9800;
        color: white;
        border: none;
        border-radius: 5px;
        padding: 10px 20px;
        cursor: pointer;
        margin: 5px 0;
    }

    .premium-button:hover {
        background-color: #e68900;
    }

    .premium-status {
        margin-top: 15px;
    }

    .tool-button {
        background-color: #4caf50;
        color: white;
        border: none;
        border-radius: 5px;
        padding: 10px 15px;
        cursor: pointer;
        margin-top: 10px;
    }

    .tool-button:hover {
        background-color: #45a049;
    }
  </style>
</body>

</html>
