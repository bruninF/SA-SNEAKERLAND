<?php


// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] !== 'funcionario') {
    header("Location: index.php");
    exit;
}

if (isset($_SESSION['mensagem_sucesso'])) {
    echo "<script>
        window.onload = function() {
            alert('" . $_SESSION['mensagem_sucesso'] . "');
        };
    </script>";
    unset($_SESSION['mensagem_sucesso']);
}
?>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;500;700&display=swap">
<link rel="stylesheet" href="css/header.css"> <!-- Arquivo CSS externo para os estilos -->
<header>
    <div class="left-icons">
        <div class="menu-icon" onclick="toggleMenu()">☰</div>
        <a href="index.php" class="user-icon">
            <i class="fas fa-user"></i>
        </a>
        <span class="user-type"> <!-- Tipo de usuário ao lado do menu -->
        <?php echo htmlspecialchars($_SESSION['nome_usuario']); ?>
        </span>
    </div>
    <div class="header-content">
        <a href="HomeFuncionario.php" class="logo">SNEAKERLAND</a>
    </div>
    <div class="right-logo">
        <img src="Logo.png" alt="Sneakerland Logo" class="logo-img">
    </div>
</header>
<!-- Menu lateral -->
<nav class="menu" id="menu">
    <div class="menu-title">Menu</div>
    <ul class="menu-content">
        <li><a href="logout.php">Sair</a></li>
        <li><a href="pesquisarProduto.php">Produtos</a></li>
        <li><a href="consultarClientes.php">Clientes</a></li>
        <li><a href="consultarFornecedores.php">Fornecedores</a></li>
        <li><a href="sobreNos2.php">Sobre-Nós</a></li>
    </ul>
</nav>

<script>
    function toggleMenu() {
        var menu = document.getElementById('menu');
        menu.classList.toggle('active');
    }

    document.addEventListener('click', function(event) {
        var menu = document.getElementById('menu');
        var menuIcon = document.querySelector('.menu-icon');

        if (!menu.contains(event.target) && !menuIcon.contains(event.target)) {
            menu.classList.remove('active');
        }
    });
</script>