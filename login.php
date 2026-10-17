<?php
session_start();
header('Content-Type: application/json');
include 'conexao.php';

$response = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $telefone = trim($_POST['telefone']);
    $senha = trim($_POST['senha']);

    $telefone = $conn->real_escape_string($telefone);
    $senha = $conn->real_escape_string($senha);

    $sql = "SELECT * FROM usuarios WHERE telefone = '$telefone' AND senha = '$senha'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION['usuario_id'] = $row['id'];
        
        // 1. Verificar se é Admin
        if ($row['nivel_acesso'] == 1) {
            $response['status'] = 'admin';
        } 
        else {
            // 2. Se é Player, verifica se está aprovado
            $statusConta = $row['status']; // pendente ou aprovado
            $nomeAvatar = $row['nome_avatar'];

            if ($statusConta === 'aprovado') {
                $response['status'] = 'aprovado'; // Vai para Pagina Principal
                $response['avatar'] = $nomeAvatar;
            } else {
                $response['status'] = 'analise'; // Vai para Em Análise
                $response['avatar'] = $nomeAvatar;
            }
        }
    } else {
        $response['status'] = 'erro';
        $response['mensagem'] = 'Telefone ou senha incorretos.';
    }
} else {
    $response['status'] = 'erro';
    $response['mensagem'] = 'Erro de requisição.';
}

echo json_encode($response);
$conn->close();
?>