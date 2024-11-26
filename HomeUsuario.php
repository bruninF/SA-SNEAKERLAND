<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] !== 'cliente') {
    // Se não estiver logado ou não for cliente, redireciona para o login
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Inicial do Usuário - Sneakerland</title>
    <link rel="stylesheet" href="css/HomeUsuario.css">
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;500;700&display=swap" rel="stylesheet">
</head>

<body>
    <?php include 'header.php'; ?> <!-- Inclui o header e o menu lateral -->

    <main>
        <div class="welcome-message">
            <h2>Bem-vindo, <?php echo htmlspecialchars($_SESSION['nome_usuario']); ?>!</h2>
            <p>Explore nossos produtos e faça suas compras com conforto e estilo.</p>
        </div>
        <div class="navigation">
            <a href="vestuario.php"><button class="nav-btn">Vestuário</button></a>
            <a href="catRoupas.php"><button class="nav-btn">Roupas</button></a>
            <a href="catTenis.php"><button class="nav-btn">Tênis</button></a>
            <a href="marcas.php"><button class="nav-btn">Marcas</button></a>
        </div>
    </main>

    <script>
        <?php if (isset($_SESSION['success_message'])): ?>
            alert("<?php echo $_SESSION['success_message']; ?>");
            <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error_message'])): ?>
            alert("<?php echo $_SESSION['error_message']; ?>");
            <?php unset($_SESSION['error_message']); ?>
        <?php endif; ?>
    </script>
</body>

</html>