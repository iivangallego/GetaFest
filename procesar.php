<?php

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.php");
    exit;
}

$nombre = $_POST["nombre"] ?? "";
$apellidos = $_POST["apellidos"] ?? "";
$email = $_POST["email"] ?? "";
$edadTexto = $_POST["edad"] ?? "";
$tipo = $_POST["tipo"] ?? "";
$dias = $_POST["dias"] ?? [];
$pago = $_POST["pago"] ?? "";
$observaciones = $_POST["observaciones"] ?? "";

if(
        !is_string($nombre) ||
        !is_string($apellidos) ||
        !is_string($email) ||
        !is_string($edadTexto) ||
        !is_string($tipo) ||
        !is_array($dias) ||
        !is_string($pago) ||
        !is_string($observaciones)
){
    echo "Error: los datos enviados no son válidos.";
    exit;
}

$nombre = trim($nombre);
$apellidos = trim($apellidos);
$email = trim($email);
$edadTexto = trim($edadTexto);
$observaciones = trim($observaciones);

if(
        $nombre === "" ||
        $apellidos === "" ||
        !filter_var($email, FILTER_VALIDATE_EMAIL) ||
        !ctype_digit($edadTexto)
){
    echo "Error: revisa el nombre, los apellidos, la edad y el correo.";
    exit;
}

$edad = (int)$edadTexto;

if ($edad < 18 || $edad > 120) {
    echo "Error: GetaFest es exclusivo para mayores de edad.";
    exit;
}

$precios = [
        "general" => 50,
        "vip" => 120,
        "supervip" => 180
];

$nombresTipos = [
        "general" => "General",
        "vip" => "VIP con acceso a Backstage",
        "supervip" => "Super VIP + Camping"
];

if (!isset($precios[$tipo])) {
    echo "Error: selecciona un tipo de entrada válido.";
    exit;
}

$diasPermitidos = ["viernes", "sabado", "domingo"];

$nombresDias = [
        "viernes" => "Viernes",
        "sabado" => "Sábado",
        "domingo" => "Domingo"
];

foreach ($dias as $dia) {
    if (
            !is_string($dia) ||
            !in_array($dia, $diasPermitidos, true)
    ){
        echo "Error: uno de los días seleccionados no es válido.";
        exit;
    }
}

$metodosPago = [
        "tarjeta" => "Tarjeta de crédito",
        "bizum" => "Bizum",
        "paypal" => "PayPal"
];
if (!isset($metodosPago[$pago])) {
    echo "Error: selecciona un método de pago válido.";
    exit;
}

if (
        !isset($_FILES["foto"]) ||
        !isset($_FILES["foto"]["error"]) ||
        !isset($_FILES["foto"]["tmp_name"]) ||
        !isset($_FILES["foto"]["size"]) ||
        $_FILES["foto"]["error"] !== UPLOAD_ERR_OK ||
        !is_uploaded_file($_FILES["foto"]["tmp_name"])
) {
    echo "Error: debes subir una fotografía correctamente.";
    exit;
}

if (
        !is_numeric($_FILES["foto"]["size"]) ||
        $_FILES["foto"]["size"] <= 0 ||
        $_FILES["foto"]["size"] > 5 * 1024 * 1024
) {
    echo "Error: la foto debe ocupar menos de 5 MB.";
    exit;
}

$informacionImagen = getimagesize($_FILES["foto"]["tmp_name"]);

if ($informacionImagen === false) {
    echo "Error: el archivo subido no es una imagen válida.";
    exit;
}

$tipoReal = mime_content_type($_FILES["foto"]["tmp_name"]);

$extensiones = [
        "image/jpeg" => "jpg",
        "image/png" => "png",
        "image/webp" => "webp"
];

if (
        !isset($extensiones[$tipoReal]) ||
        $informacionImagen["mime"] !== $tipoReal
) {
    echo "Error: la foto debe estar en formato JPG, PNG o WEBP.";
    exit;
}

if (!is_dir("images") && !mkdir("images", 0755, true)) {
    echo "Error: no se ha podido crear la carpeta de imágenes.";
    exit;
}

$nombreFoto = uniqid("getafest_", true) . "." . $extensiones[$tipoReal];
$rutaFoto = "images/" . $nombreFoto;

if (!move_uploaded_file($_FILES["foto"]["tmp_name"], $rutaFoto)) {
    echo "Error: no se ha podido guardar la fotografía.";
    exit;
}

$precioBase = $precios[$tipo];
$suplemento = count($dias) * 10;
$precioTotal = $precioBase + $suplemento;
$diasTexto = [];

foreach ($dias as $dia) {
    $diasTexto[] = $nombresDias[$dia];
}

if (!empty($diasTexto)) {
    $diasMostrar = implode(", ", $diasTexto);
} else {
    $diasMostrar = "No se han seleccionado días";
}

?>

<?php include "header.php"; ?>

<main class="ticket">

    <div class="ticket-cabecera">
        <h1>GetaFest</h1>
        <p>ACREDITACIÓN OFICIAL · PASE DIGITAL</p>
    </div>

    <div class="ticket-cuerpo">

        <img
                class="ticket-foto"
                src="<?php echo htmlspecialchars($rutaFoto, ENT_QUOTES, "UTF-8"); ?>"
                alt="Foto del asistente"
        >

        <div class="ticket-datos">
            <h2>Datos del asistente</h2>
            <p>
                <strong>Nombre:</strong>
                <?php
                echo htmlspecialchars(
                        $nombre . " " . $apellidos,
                        ENT_QUOTES,
                        "UTF-8"
                );
                ?>
            </p>
            <p>
                <strong>Email:</strong>
                <?php echo htmlspecialchars($email, ENT_QUOTES, "UTF-8"); ?>
            </p>
            <p>
                <strong>Edad:</strong>
                <?php echo $edad; ?> años
            </p>
            <p>
                <strong>Tipo de pase:</strong>
                <?php
                echo htmlspecialchars(
                        $nombresTipos[$tipo],
                        ENT_QUOTES,
                        "UTF-8"
                );
                ?>
            </p>
            <p>
                <strong>Días de asistencia:</strong>
                <?php
                echo htmlspecialchars(
                        $diasMostrar,
                        ENT_QUOTES,
                        "UTF-8"
                );
                ?>
            </p>
            <p>
                <strong>Método de pago:</strong>
                <?php
                echo htmlspecialchars(
                        $metodosPago[$pago],
                        ENT_QUOTES,
                        "UTF-8"
                );
                ?>
            </p>
            <p>
                <strong>Observaciones:</strong>
                <?php
                if ($observaciones !== "") {
                    echo nl2br(
                            htmlspecialchars(
                                    $observaciones,
                                    ENT_QUOTES,
                                    "UTF-8"
                            )
                    );
                } else {
                    echo "Ninguna";
                }
                ?>
            </p>
        </div>

    </div>
    <div class="ticket-precio">
        <h2>Desglose del precio</h2>
        <p>
            Precio base:
            <?php echo number_format($precioBase, 2, ",", "."); ?> €
        </p>
        <p>
            Suplemento por <?php echo count($dias); ?> día(s):
            <?php echo number_format($suplemento, 2, ",", "."); ?> €
        </p>
        <p class="ticket-total">
            Total a pagar:
            <?php echo number_format($precioTotal, 2, ",", "."); ?> €
        </p>
    </div>
    <div class="ticket-acciones">
        <a class="ticket-boton" href="index.php">
            Volver al formulario
        </a>
    </div>
</main>
<?php include "footer.php"; ?>

