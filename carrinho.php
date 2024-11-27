<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] !== 'cliente') {
    // Se não estiver logado ou não for administrador, redireciona para o login
    header("Location: index.php");
    exit;
}
// Inicializa o carrinho se não estiver criado ainda
if (!isset($_SESSION['carrinho'])) {
    $_SESSION['carrinho'] = [];
}

// Adiciona o produto ao carrinho
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['produto_id'])) {
        $produtoId = $_POST['produto_id'];

        // Adiciona ou incrementa a quantidade do produto no carrinho
        if (isset($_SESSION['carrinho'][$produtoId])) {
            $_SESSION['carrinho'][$produtoId]['quantidade'] += 1;
        } else {
            $_SESSION['carrinho'][$produtoId] = ['quantidade' => 1];
        }

        header("Location: carrinho.php");
        exit();
    }
    // Remove o produto do carrinho
    if (isset($_POST['remover_id'])) {
        $removerId = $_POST['remover_id'];
        unset($_SESSION['carrinho'][$removerId]);

        header("Location: carrinho.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrinho</title>
    <link rel="stylesheet" href="css/carrinho.css">
</head>

<body>
    <?php include 'header.php'; ?>
    <div class="carrinho-container">
        <?php
        // Conexão com o banco de dados
        $conn = new mysqli("localhost", "root", "", "sneakerland");

        if ($conn->connect_error) {
            die("Conexão falhou: " . $conn->connect_error);
        }

        if (!empty($_SESSION['carrinho'])) {
            $total = 0;
            foreach ($_SESSION['carrinho'] as $produtoId => $item) {
                $sql = "SELECT nome, preco FROM produtos WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $produtoId);
                $stmt->execute();
                $result = $stmt->get_result();
                $produto = $result->fetch_assoc();

                $subtotal = $produto['preco'] * $item['quantidade'];
                $total += $subtotal;

                echo "<div class='produto'>";
                echo "<p>Produto: " . htmlspecialchars($produto['nome']) . "</p>";
                echo "<p>Preço: R$ " . number_format($produto['preco'], 2, ',', '.') . "</p>";
                echo "<p>Quantidade: " . htmlspecialchars($item['quantidade']) . "</p>";
                echo "<p>Subtotal: R$ " . number_format($subtotal, 2, ',', '.') . "</p>";
                echo "<form method='post' action='carrinho.php'>
                        <input type='hidden' name='remover_id' value='" . $produtoId . "'>
                        <button type='submit' class='remover-btn'>Remover</button>
                      </form>";
                echo "</div><hr>";
            }
            echo "<p class='total'>Total: R$ " . number_format($total, 2, ',', '.') . "</p>";
            echo "<a href='pagamento.php' class='finalizar-compra'>Finalizar Compra</a>";
        } else {
            echo "<p>Seu carrinho está vazio.</p>";
        }

        $conn->close();
        ?>
        <a href="HomeUsuario.php" class="continuar-comprando">Continuar Comprando</a>
    </div>
    
</body>

</html>