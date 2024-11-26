<?php
// header.php

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] !== 'cliente') {
    header("Location: index.php");
    exit;
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
        <a href="HomeUsuario.php" class="logo">SNEAKERLAND</a>
    </div>
    <div class="right-logo">
        <img src="Logo.png" alt="Sneakerland Logo" class="logo-img">
    </div>
</header>

<!-- Menu Lateral -->
<nav id="sidebar" class="menu">
    <div class="close-btn" onclick="toggleMenu()">×</div>
    <div class="menu-title">Menu</div>
    <ul class="menu-content">
        <li><a href="HomeUsuario.php">Início</a></li>
        <li><a href="catRoupas.php">Roupas</a></li>
        <li><a href="catTenis.php">Tênis</a></li>
        <li><a href="vestuario.php">Vestuário</a></li>
        <li><a href="carrinho.php">Carrinho</a></li>
        <li><a href="pedidos.php">Pedidos</a></li>
        <li><a href="logout.php">Sair</a></li>
    </ul>
</nav>

<script>
    function toggleMenu() {
        var menu = document.getElementById('sidebar');
        menu.classList.toggle('active');
    }

    document.addEventListener('click', function(event) {
        var menu = document.getElementById('sidebar');
        var menuIcon = document.querySelector('.menu-icon');

        if (!menu.contains(event.target) && !menuIcon.contains(event.target)) {
            menu.classList.remove('active');
        }
    });
</script>
