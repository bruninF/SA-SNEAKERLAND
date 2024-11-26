<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] !== 'administrador') {
    // Se não estiver logado ou não for administrador, redireciona para o login
    header("Location: index.php");
    exit;
}

if (isset($_SESSION['mensagem_sucesso'])) {
    // Armazenar a mensagem em uma variável JavaScript
    echo "<script>
        window.onload = function() {
            alert('" . $_SESSION['mensagem_sucesso'] . "');
        };
    </script>";

    // Remover a mensagem da sessão para que não apareça novamente ao recarregar a página
    unset($_SESSION['mensagem_sucesso']);
}

?>
<?php include 'headerADM.php'; ?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Inicial do Usuário - Sneakerland</title>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/HomeADM.css">
</head>

<body>

    <main>
        <div class="welcome-message">
            <h2>Bem-vindo, <?php echo htmlspecialchars($_SESSION['nome_usuario']); ?>!</h2>
        </div>
        <div class="navigation">
            <a href="clientes.php"><button class="nav-btn">Gerenciar Usuários</button></a>
            <a href="funcionarios.php"><button class="nav-btn">Gerenciar Funcionários</button></a>
            <a href="gerenciar_pedidos.php"><button class="nav-btn">Gerenciar Pedidos</button></a>
            <div class="nav-btn-container">
                <button class="nav-btn">Adicionar Item</button>
                <div class="submenu">
                    <a href="CadTenis.php">Tênis</a>
                    <a href="CadRoupas.php">Roupas</a>
                </div>
            </div>
        </div>
    </main>

</body>

</html>
