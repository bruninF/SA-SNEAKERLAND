<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] !== 'cliente') {
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
    <title>Detalhes do Produto</title>
    <link rel="stylesheet" href="css/produto.css"> <!-- Link para um arquivo CSS externo -->
</head>

<body>
    <?php include 'header.php'; ?>
    <div class="produto-container">
        <?php
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "sneakerland";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Conexão falhou: " . $conn->connect_error);
        }

        $nome_produto = isset($_GET['nome']) ? $_GET['nome'] : '';

        $sql = "SELECT id, nome, preco, imagem_url, marca, descricao, tamanho, cor, estoque FROM produtos WHERE nome = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $nome_produto);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $produtos = [];
            while ($row = $result->fetch_assoc()) {
                $produtos[] = $row;
            }

            echo "<h1>" . htmlspecialchars($produtos[0]["nome"]) . "</h1>";
            echo "<p>Marca: " . htmlspecialchars($produtos[0]["marca"]) . "</p>";
            echo "<p>Preço: R$ <span id='preco-valor'>" . number_format($produtos[0]["preco"], 2, ',', '.') . "</span></p>";
            echo "<img src='" . htmlspecialchars($produtos[0]["imagem_url"]) . "' alt='" . htmlspecialchars($produtos[0]["nome"]) . "'>";
            echo "<p>Descrição: <span id='descricao'>" . htmlspecialchars($produtos[0]["descricao"]) . "</span></p>";

            echo "<label for='tamanho-select'>Escolha um tamanho:</label>";
            echo "<select id='tamanho-select'>";
            foreach ($produtos as $produto) {
                echo "<option value='" . $produto['id'] . "' 
                      data-preco='" . $produto['preco'] . "' 
                      data-descricao='" . htmlspecialchars($produto['descricao']) . "' 
                      data-cor='" . htmlspecialchars($produto['cor']) . "' 
                      data-estoque='" . $produto['estoque'] . "'>"
                    . htmlspecialchars($produto['tamanho']) . "</option>";
            }
            echo "</select>";

            echo "<div class='info'>";
            echo "<p id='cor'>Cor: <span id='cor-valor'>" . htmlspecialchars($produtos[0]["cor"]) . "</span></p>";
            echo "<p id='estoque'>Estoque: <span id='estoque-valor'>" . htmlspecialchars($produtos[0]["estoque"]) . "</span></p>";
            echo "</div>";

            echo "<button id='adicionar-carrinho'>Adicionar ao Carrinho</button>";
        } else {
            echo "Produto não encontrado.";
        }

        $stmt->close();
        $conn->close();
        ?>
    </div>

    <script>
        document.getElementById('tamanho-select').addEventListener('change', function() {
            var selectedOption = this.options[this.selectedIndex];
            var preco = selectedOption.getAttribute('data-preco');
            var descricao = selectedOption.getAttribute('data-descricao');
            var cor = selectedOption.getAttribute('data-cor');
            var estoque = selectedOption.getAttribute('data-estoque');

            document.getElementById('preco-valor').innerText = parseFloat(preco).toLocaleString('pt-BR', {
                minimumFractionDigits: 2
            });
            document.getElementById('descricao').innerText = descricao;
            document.getElementById('cor-valor').innerText = cor;
            document.getElementById('estoque-valor').innerText = estoque;
        });

        document.getElementById('adicionar-carrinho').addEventListener('click', function() {
            var selectedOption = document.getElementById('tamanho-select').options[document.getElementById('tamanho-select').selectedIndex];
            var produtoId = selectedOption.value;

            // Criação do formulário para envio via POST
            var form = document.createElement('form');
            form.method = 'POST';
            form.action = 'carrinho.php';

            // Adiciona o campo de produtoId
            var input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'produto_id';
            input.value = produtoId;

            form.appendChild(input);
            document.body.appendChild(form);

            // Submete o formulário e redireciona para carrinho.php
            form.submit();
        });
    </script>

</body>

</html>