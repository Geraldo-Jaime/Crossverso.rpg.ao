<?php
// NÃO use localhost. Use o que está no painel do Unaux.
// Exemplo: sql304.unaux.com (Verifique o seu exato!)
$host = "sql306.ezyro.com"; 

$user = "ezyro_40929620"; // O seu utilizador do painel
$pass = "Xp36@G22"; 
$db   = "ezyro_40929620_unaux_12345_rpg"; 

$conn = new mysqli($host, $user, $pass, $db);
// ... resto do código
?><?php

// DADOS RETIRADOS DA SUA IMAGEM:

$host = "sql306.ezyro.com";  // MySQL Host Name (Está na imagem)

$user = "ezyro_40929620";    // MySQL User Name (Está na imagem)

$db   = "ezyro_40929620_unaux_12345_rpg"; // MySQL DB Name (Está na imagem)

$pass = "Xp36@G22"; 

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {

    die("Falha na conexão: " . $conn->connect_error);

}

?>