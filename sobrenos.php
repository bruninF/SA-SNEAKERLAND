<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] !== 'administrador') {
    // Se não estiver logado ou não for administrador, redireciona para o login
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sobre Nós - Sneakerland</title>
    <link rel="stylesheet" href="css/sobrenos.css"> 
</head>
<body>
    <?php include 'headerADM.php'; ?>
    <div class="about-container">
        <h1>Sobre Nós</h1>
        <p>Bem-vindo à Sneakerland, sua loja de confiança para tênis autênticos e de alta qualidade. Nossa missão é proporcionar aos nossos clientes uma experiência única, oferecendo uma seleção diversificada dos melhores sneakers das marcas mais renomadas do mundo.</p>
        <p>Fundada com o objetivo de conectar entusiastas de tênis e amantes da moda urbana, a Sneakerland combina paixão e conhecimento para trazer até você os lançamentos mais desejados e edições limitadas. Cada par de tênis é cuidadosamente selecionado para garantir autenticidade e qualidade superior.</p>
        <p>Agradecemos pela sua visita e estamos sempre à disposição para ajudar. Nossa equipe de atendimento ao cliente está pronta para responder a todas as suas dúvidas e oferecer uma experiência de compra memorável.</p>
    </div>
    
</body>
</html>