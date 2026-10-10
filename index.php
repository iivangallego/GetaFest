<?php include "header.php"; ?>

<form method="POST" action="procesar.php" enctype="multipart/form-data">
    <h2>Reserva tu pase</h2>

    <label for="nombre">Nombre:</label>
    <input type="text" id="nombre" name="nombre" required>

    <label for="apellidos">Apellidos:</label>
    <input type="text" id="apellidos" name="apellidos" required>

    <label for="edad">Edad:</label>
    <input type="number" id="edad" name="edad" min="1" required>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>

    <div class="Configuración del Pase">
        <p>Selecciona un tipo de entrada:</p>
        <label class="opcion-radio"><input type="radio" name="tipo" value="general" required> General (50 €)</label>
        <label class="opcion-radio"><input type="radio" name="tipo" value="vip"> VIP con acceso a Backstage (120 €)</label>
        <label class="opcion-radio"><input type="radio" name="tipo" value="supervip"> Super VIP + Camping (180 €)</label>
    </div>

    <div class="Dias asistencia">
        <p>Selecciona los días de asistencia (+10 € por día):</p>
        <label for="viernes"><input type="checkbox" id="viernes" name="dias[]" value="viernes"> Viernes</label>
        <label for="sabado"><input type="checkbox" id="sabado" name="dias[]" value="sabado"> Sábado</label>
        <label for="domingo"><input type="checkbox" id="domingo" name="dias[]" value="domingo"> Domingo</label>
    </div>

    <div class="metodo pago">
        <label for="pago">Método de pago:</label>
        <select id="pago" name="pago" required>
            <option value="">-- Selecciona --</option>
            <option value="tarjeta">Tarjeta de crédito</option>
            <option value="bizum">Bizum</option>
            <option value="paypal">PayPal</option>
        </select>
    </div>

    <div class="acreditacion">
        <label for="foto">Foto del asistente:</label>
        <input type="file" id="foto" name="foto" accept="image/jpeg,image/png,image/webp" required>
    </div>

    <div class="observaciones">
        <label for="observaciones">Observaciones:</label>
        <textarea id="observaciones" name="observaciones" placeholder="Comentarios o peticiones especiales..."></textarea>
    </div>

    <div class="enviar">
        <input type="submit" class="btn" value="Crear acreditación">
    </div>
</form>
<?php include "footer.php"; ?>
