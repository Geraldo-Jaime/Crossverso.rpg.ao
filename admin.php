<?php
session_start();
include 'conexao.php';

/*
if (!isset($_SESSION['usuario_id'])) {
    header("Location: iniciar-sessao.html");
    exit();
}
*/
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Admin - CrosMaster</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* (MANTIVE O SEU CSS ORIGINAL AQUI PARA NÃO ESTRAGAR O DESIGN) */
        :root { --primary-color: #FFFFFF; --bg-dark: #1a1a1d; --bg-panel: #2d2d30; --bg-card: #1e1e1e; --text-light: #f1f1f1; --text-gray: #a0a0a0; --success: #4caf50; --danger: #f44336; }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background-color: var(--bg-dark); color: var(--text-light); display: flex; min-height: 100vh; }
        .sidebar { width: 250px; background-color: #000; padding: 20px; display: flex; flex-direction: column; border-right: 1px solid #333; }
        .logo { font-size: 24px; font-weight: bold; color: var(--primary-color); margin-bottom: 40px; text-align: center; border-bottom: 1px solid #333; padding-bottom: 20px; }
        .menu-item { padding: 15px; cursor: pointer; border-radius: 8px; margin-bottom: 10px; transition: 0.3s; color: var(--text-gray); display: flex; align-items: center; gap: 10px; }
        .menu-item:hover, .menu-item.active { background-color: var(--primary-color); color: #000; font-weight: bold; }
        .main-content { flex: 1; padding: 30px; overflow-y: auto; }
        header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; border-bottom: 1px solid #333; padding-bottom: 20px; }
        .section-title { font-size: 24px; color: white; }
        .section { display: none; } .section.active { display: block; }
        
        /* CARD DE APROVAÇÃO */
        .approval-card { background-color: var(--bg-card); padding: 20px; border-radius: 12px; margin-bottom: 15px; display: flex; justify-content: space-between; align-items: center; border-left: 5px solid var(--primary-color); box-shadow: 0 4px 6px rgba(0,0,0,0.3); }
        .user-info h4 { margin-bottom: 8px; font-size: 18px; color: white; }
        .user-info p { font-size: 14px; color: var(--text-gray); line-height: 1.4; }
        .actions-column { display: flex; flex-direction: column; gap: 10px; }
        .btn-circle { width: 40px; height: 40px; border-radius: 50%; border: none; cursor: pointer; display: flex; justify-content: center; align-items: center; font-size: 16px; color: white; transition: 0.2s; }
        .btn-approve-circle { background-color: var(--success); }
        .btn-reject-circle { background-color: var(--danger); }
        .btn-approve-circle:hover, .btn-reject-circle:hover { transform: scale(1.1); }
        
        @media (max-width: 768px) { body { flex-direction: column; } .sidebar { width: 100%; flex-direction: row; overflow-x: auto; padding: 10px; } .logo { display: none; } }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <nav class="sidebar">
        <div class="logo"><i class="fas fa-crown"></i> CrosMaster</div>
        
        <!-- Badge PHP: Conta quantos pendentes existem -->
        <?php
            $sqlCount = "SELECT COUNT(*) as total FROM usuarios WHERE status = 'pendente'";
            $resCount = $conn->query($sqlCount);
            $totalPendentes = $resCount->fetch_assoc()['total'];
        ?>
        
        <div class="menu-item active" onclick="showSection('approvals', this)">
            <i class="fas fa-user-plus"></i> Aprovações 
            <?php if($totalPendentes > 0): ?>
                <span id="badge-count" style="background:red; padding:2px 6px; border-radius:10px; font-size:12px; margin-left:auto; color:white;">
                    <?php echo $totalPendentes; ?>
                </span>
            <?php endif; ?>
        </div>

        <div class="menu-item" onclick="showSection('users', this)"><i class="fas fa-users"></i> Gerenciar Players</div>
        <div class="menu-item" onclick="logout()" style="margin-top: auto; color: var(--danger);"><i class="fas fa-sign-out-alt"></i> Sair</div>
    </nav>

    <!-- Conteúdo Principal -->
    <main class="main-content">
        <header>
            <h2 class="section-title" id="page-title">Aprovações Pendentes</h2>
            <div class="admin-profile">
                <span>Admin: <strong>CrosMaster</strong></span>
            </div>
        </header>

        <!-- SEÇÃO 1: APROVAÇÕES (DINÂMICA) -->
        <section id="approvals" class="section active">
            
            <?php
            // BUSCAR DADOS REAIS DO BANCO DE DADOS
            $sql = "SELECT * FROM usuarios WHERE status = 'pendente' ORDER BY id DESC";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // Se houver usuários, cria um card para cada um
                while($row = $result->fetch_assoc()) {
                    $id = $row['id'];
                    $nome = $row['nome_proprio'];
                    $avatar = $row['nome_avatar'];
                    $tel = $row['telefone'];
                    $pais = $row['pais']; // Se tiver coluna pais
                    
                    echo "
                    <div class='approval-card' id='card-user-$id'>
                        <div class='user-info'>
                            <h4>Avatar: $avatar <small style='color:#888'>($pais)</small></h4>
                            <p>Nome Real: $nome</p>
                            <p>Tel: $tel</p>
                        </div>
                        <div class='actions-column'>
                            <button class='btn-circle btn-approve-circle' onclick=\"processarUsuario($id, 'aprovar')\">
                                <i class='fas fa-check'></i>
                            </button>
                            <button class='btn-circle btn-reject-circle' onclick=\"processarUsuario($id, 'rejeitar')\">
                                <i class='fas fa-times'></i>
                            </button>
                        </div>
                    </div>";
                }
            } else {
                echo "<p style='text-align:center; margin-top:50px; color:#666;'>Nenhum usuário pendente no momento.</p>";
            }
            ?>

        </section>

        <!-- SEÇÃO 2: LISTA (Exemplo simples) -->
        <section id="users" class="section">
            <h3 style="color:white">Lista de Players Aprovados</h3>
            <p style="color:#aaa">Aqui você pode implementar a lista completa depois.</p>
        </section>

    </main>

    <script>
        // FUNÇÃO AJAX PARA APROVAR/REJEITAR SEM RECARREGAR A PÁGINA
        function processarUsuario(idUsuario, acao) {
            let pergunta = acao === 'aprovar' ? "Tem certeza que quer APROVAR este usuário?" : "Tem certeza que quer APAGAR este usuário?";
            
            if(!confirm(pergunta)) return;

            const formData = new FormData();
            formData.append('acao', acao);
            formData.append('id', idUsuario);

            fetch('admin_acoes.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                if(data.trim() === 'sucesso') {
                    // Remove o card da tela com animação
                    document.getElementById('card-user-' + idUsuario).style.display = 'none';
                    
                    // Atualiza o contador (Badge)
                    let badge = document.getElementById('badge-count');
                    if(badge) {
                        let total = parseInt(badge.innerText);
                        badge.innerText = total - 1;
                        if(total - 1 <= 0) badge.style.display = 'none';
                    }
                } else {
                    alert("Erro ao processar: " + data);
                }
            })
            .catch(error => alert("Erro de conexão"));
        }

        // Navegação das abas
        function showSection(sectionId, element) {
            document.querySelectorAll('.section').forEach(sec => sec.classList.remove('active'));
            document.querySelectorAll('.menu-item').forEach(item => item.classList.remove('active'));
            document.getElementById(sectionId).classList.add('active');
            element.classList.add('active');
        }

        function logout() {
            if(confirm("Sair do Painel Admin?")) {
                window.location.href = "iniciar-sessao.html";
            }
        }
    </script>
</body>
</html>