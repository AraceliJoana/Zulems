<?php
$host = "localhost";
$usuario = "root";
$contrasena = "";
$base_datos = "zulem";

// Conectar a la base de datos
$conn = new mysqli($host, $usuario, $contrasena, $base_datos);
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener datos del JSON recibido
$data = json_decode(file_get_contents("php://input"), true);

$productos = json_encode($data['productos']);
$total = $data['total'];
$numero = $data['tarjeta']['numero'];
$titular = $data['tarjeta']['titular'];
$expira = $data['tarjeta']['expira'];
$cvc = $data['tarjeta']['cvc'];

$sql = "INSERT INTO carritos (productos, total, tarjeta_numero, tarjeta_titular, tarjeta_expira, tarjeta_cvc)
        VALUES (?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Error en prepare(): " . $conn->error);
}

$stmt->bind_param("sdssss", $productos, $total, $numero, $titular, $expira, $cvc);

if ($stmt->execute()) {
    echo "Carrito guardado correctamente.";
} else {
    echo "Error al guardar: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
