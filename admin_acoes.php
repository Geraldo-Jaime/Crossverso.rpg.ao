<?php
include 'conexao.php';

// Verifica se recebeu dados
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $acao = $_POST['acao'];
    $id = $_POST['id'];

    if ($acao == 'aprovar') {
        // Muda o status para 'aprovado'
        $sql = "UPDATE usuarios SET status = 'aprovado' WHERE id = '$id'";
        if ($conn->query($sql)) { echo "sucesso"; } else { echo "erro"; }
    } 
    elseif ($acao == 'rejeitar') {
        // Apaga o usuário do banco de dados
        $sql = "DELETE FROM usuarios WHERE id = '$id'";
        if ($conn->query($sql)) { echo "sucesso"; } else { echo "erro"; }
    }
}
$conn->close();
?>