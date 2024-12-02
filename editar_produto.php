<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] !== 'administrador') {
    // Se não estiver logado ou não for administrador, redireciona para o login
    header("Location: index.php");
    exit;
}
// Conexão com o banco de dados
$conn = new mysqli('localhost', 'root', '', 'sneakerland');
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Verificar se o ID do produto foi enviado
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $query = "SELECT * FROM produtos WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $produto = $result->fetch_assoc();

    if (!$produto) {
        die("Produto não encontrado.");
    }
} else {
    die("ID do produto não fornecido.");
}

// Atualizar os dados
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $preco = $_POST['preco'];
    $estoque = $_POST['estoque'];
    $categoria = $_POST['categoria'];
    $imagem_url = $_POST['imagem_url'];
    $marca = $_POST['marca'];
    $tamanho = $_POST['tamanho'];
    $cor = $_POST['cor'];

    $update_query = "UPDATE produtos SET nome = ?, descricao = ?, preco = ?, estoque = ?, categoria = ?, imagem_url = ?, marca = ?, tamanho = ?, cor = ? WHERE id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("ssdisssssi", $nome, $descricao, $preco, $estoque, $categoria, $imagem_url, $marca, $tamanho, $cor, $id);

    if ($stmt->execute()) {
        echo "Produto atualizado com sucesso!";
    } else {
        echo "Erro ao atualizar o produto: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Produto</title>
    <style>
        body {
        font-family: 'Oswald', sans-serif;
        margin: 0;
        padding: 0;
        background-color: #f4f4f4;
        color: #333;
        }
        form {
            max-width: 600px;
            margin: auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        label {
            display: block;
            margin: 10px 0 5px;
        }
        input, textarea {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
        }
        button {
            padding: 10px 15px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        h1{
            text-align: center;
        }
    </style>
</head>
<body>
    <?php include 'headerADM.php'; ?>
    <h1>Editar Produto</h1>
    <form method="post">
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($produto['nome']) ?>" required>

        <label for="descricao">Descrição:</label>
        <textarea id="descricao" name="descricao"><?= htmlspecialchars($produto['descricao']) ?></textarea>

        <label for="preco">Preço:</label>
        <input type="number" id="preco" name="preco" step="0.01" value="<?= htmlspecialchars($produto['preco']) ?>" required>

        <label for="estoque">Estoque:</label>
        <input type="number" id="estoque" name="estoque" value="<?= htmlspecialchars($produto['estoque']) ?>" required>

        <label for="categoria">Categoria:</label>
        <input type="text" id="categoria" name="categoria" value="<?= htmlspecialchars($produto['categoria']) ?>" required>

        <label for="imagem_url">URL da Imagem:</label>
        <input type="text" id="imagem_url" name="imagem_url" value="<?= htmlspecialchars($produto['imagem_url']) ?>">


        <label for="marca">Marca:</label>
        <input type="text" id="marca" name="marca" value="<?= htmlspecialchars($produto['marca']) ?>" required>

        <label for="tamanho">Tamanho:</label>
        <input type="text" id="tamanho" name="tamanho" value="<?= htmlspecialchars($produto['tamanho']) ?>">

        <label for="cor">Cor:</label>
        <input type="text" id="cor" name="cor" value="<?= htmlspecialchars($produto['cor']) ?>">

        <button type="submit">Salvar Alterações</button>
    </form>
</body>
</html>
