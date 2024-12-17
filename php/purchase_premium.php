<script>
  // Manejo de compra de premium con generación de factura
  function purchasePremium() {
      fetch('php/manage_premium.php', {
          method: 'POST',
          body: JSON.stringify({ action: 'buy', user_id: <?php echo $_SESSION['unique_id']; ?> }),
          headers: { 'Content-Type': 'application/json' }
      })
      .then(response => response.json())
      .then(data => {
          if (data.success) {
              // Generar la factura después de la compra
              fetch('php/generate_invoice.php', {
                  method: 'POST',
                  headers: { 'Content-Type': 'application/json' }
              })
              .then(invoiceResponse => invoiceResponse.json())
              .then(invoiceData => {
                  if (invoiceData.success) {
                      alert('¡Felicidades! Ahora eres usuario premium. Tu factura está lista.');
                      window.open(invoiceData.invoice_url, '_blank'); // Abrir la factura en una nueva pestaña
                  } else {
                      alert('Premium activado, pero hubo un problema al generar la factura.');
                  }
              });
              location.reload();
          } else {
              alert('Hubo un problema al procesar tu compra.');
          }
      });
  }
</script>
