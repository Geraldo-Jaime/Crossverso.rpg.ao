<?php
include 'conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Receber e limpar os dados
    $nome = $conn->real_escape_string($_POST['nome']);
    $avatar = $conn->real_escape_string($_POST['avatar']);
    $genero = $conn->real_escape_string($_POST['genero']);
    $pais = $conn->real_escape_string($_POST['pais']);
    $telefone = $conn->real_escape_string($_POST['telefone']);
    $senha = $conn->real_escape_string($_POST['senha']);

    // 2. Verificar se o telefone já existe
    $check = "SELECT id FROM usuarios WHERE telefone = '$telefone'";
    $result = $conn->query($check);

    if ($result->num_rows > 0) {
        echo "erro_existe"; // O telefone já está registado
    } else {
        // 3. Inserir novo jogador (Nivel 0 = Player normal)
        $sql = "INSERT INTO usuarios (nome_proprio, nome_avatar, genero, pais, telefone, senha, nivel_acesso) 
                VALUES ('$nome', '$avatar', '$genero', '$pais', '$telefone', '$senha', 0)";
        
        if ($conn->query($sql) === TRUE) {
            echo "sucesso";
        } else {
            echo "erro_sql: " . $conn->error;
        }
    }
}
$conn->close();
?>