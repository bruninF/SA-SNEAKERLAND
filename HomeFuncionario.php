<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] !== 'funcionario') {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Inicial do Funcionário - Sneakerland</title>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/HomeFuncionario.css">
</head>
<body>
    <?php include 'headerFuncionario.php'; ?>
    <main>
        <div class="container">
            <div class="welcome-section">
                <h2>Bem-vindo, <?php echo htmlspecialchars($_SESSION['nome_usuario']); ?>!</h2>

            </div>
            <div class="navigation">
                
                <div class="nav-card">
                    <h3>Consultar</h3>
                    <ul>
                        <li><a href="pesquisarProduto.php">Produto</a></li>
                        <li><a href="consultarClientes.php">Cliente</a></li>
                        <li><a href="consultarFornecedores.php">Fornecedor</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
