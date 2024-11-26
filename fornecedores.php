<?php
session_start();
// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] !== 'administrador') {
    // Se não estiver logado ou não for administrador, redireciona para o login
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nossos Fornecedores</title>
    <link rel="stylesheet" href="css/fornecedores.css"> <!-- Inclua o caminho do seu arquivo CSS -->
    <style>
    </style>
</head>

<body>
    <?php include 'headerADM.php'; ?>
    <div class="container fornecedores-container">
        <h1>Nossos Fornecedores</h1>
        <ul>
            <li>Nike</li>
            <li>Adidas</li>
            <li>Puma</li>
            <li>Jordan</li>
            <li>Asics</li>
            <li>Mizuno</li>
            <li>High</li>
            <li>Sufgang</li>
            <li>Hocks</li>
            <li>Ous</li>
            <li>Tesla</li>
        </ul>
    </div>
    
</body>

</html>