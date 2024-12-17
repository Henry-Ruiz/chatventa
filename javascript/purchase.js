function purchasePremium() {
    if (confirm("¿Deseas comprar la actualización premium?")) {
      fetch("php/purchase_premium.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.success) {
            alert("¡Compra realizada con éxito! Ahora puedes enviar fotos y emojis.");
            document.getElementById("premium-status").textContent = "Eres usuario premium";
          } else {
            alert(data.error || "Hubo un error al procesar tu compra.");
          }
        })
        .catch((error) => console.error("Error:", error));
    }
  }
  