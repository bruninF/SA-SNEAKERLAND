<?php
session_start(); // Iniciar a sessão

// Habilitar a exibição de erros

if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] !== 'administrador') {
    // Se não estiver logado ou não for administrador, redireciona para o login
    header("Location: index.php");
    exit;
}

// Variável para mensagens de sucesso ou erro
$mensagem = "";

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Conectar ao banco de dados
    $conn = new mysqli("localhost", "root", "", "sneakerland");

    // Verificar a conexão
    if ($conn->connect_error) {
        die("Falha na conexão: " . $conn->connect_error);
    }

    // Pegar os dados do formulário
    $nome = $_POST['nome'];
    $marca = $_POST['marca'];
    $categoria = 'tenis'; // Definido como tênis fixamente
    $preco = $_POST['preco'];
    $tamanho = $_POST['tamanho'];
    $cor = $_POST['cor'];
    $descricao = $_POST['descricao'];
    $estoque = $_POST['estoque'];

    // Verificar se uma imagem foi enviada
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
        // Verificar o tipo da imagem
        $imagem_nome = $_FILES['imagem']['name'];
        $imagem_tmp = $_FILES['imagem']['tmp_name'];
        $imagem_tipo = mime_content_type($imagem_tmp); // Obter o tipo MIME da imagem
        $tipos_permitidos = ['image/jpeg', 'image/png', 'image/gif', 'image/jfif'];
        $extensao = pathinfo($imagem_nome, PATHINFO_EXTENSION);
        $extensoes_permitidas = ['jpg', 'jpeg', 'png', 'gif', 'jfif'];

        if (in_array($extensao, $extensoes_permitidas) && in_array($imagem_tipo, $tipos_permitidos)) {
            $imagem_destino = 'uploads/' . basename($imagem_nome);

            // Mover a imagem para o diretório de uploads
            if (move_uploaded_file($imagem_tmp, $imagem_destino)) {
                // Inserir dados na tabela
                $sql_insert = "INSERT INTO produtos (nome, marca, descricao, preco, estoque, categoria, tamanho, cor, imagem_url) 
                               VALUES ('$nome', '$marca', '$descricao', '$preco', '$estoque', 'roupa', '$tamanho', '$cor', '$imagem_destino')";

                if ($conn->query($sql_insert) === TRUE) {
                    // Armazenar mensagem de sucesso na sessão
                    $_SESSION['mensagem_sucesso'] = "Tênis cadastrado com sucesso!";

                    // Redirecionar para a página HomeADM.php
                    header("Location: HomeADM.php");
                    exit;
                } else {
                    $mensagem = "Erro ao cadastrar o tênis: " . $conn->error;
                }
            } else {
                $mensagem = "Erro ao fazer upload da imagem.";
            }
        } else {
            $mensagem = "Por favor, envie uma imagem válida (JPEG, PNG ou GIF).";
        }
    } else {
        // Verificar o tipo de erro de upload
        if (isset($_FILES['imagem'])) {
            switch ($_FILES['imagem']['error']) {
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                    $mensagem = "A imagem é muito grande.";
                    break;
                case UPLOAD_ERR_NO_FILE:
                    $mensagem = "Nenhum arquivo foi enviado.";
                    break;
                default:
                    $mensagem = "Erro ao fazer upload da imagem.";
                    break;
            }
        } else {
            $mensagem = "Por favor, envie uma imagem válida.";
        }
    }

    // Fechar conexão
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Roupas</title>
    <link rel="stylesheet" href="css/cadtenis.css">
</head>

<body>
<?php include 'headerADM.php'; ?>

    <form method="POST" action="" enctype="multipart/form-data">
        <h2>Cadastro de Roupas</h2>
        <label for="nome">Nome da Roupa</label>
        <input type="text" id="nome" name="nome" required>

        <label for="marca">Marca</label>
        <input type="text" id="marca" name="marca" required>

        <label for="tamanho">Tamanho:</label>
        <input type="text" id="tamanho" name="tamanho" required>
           

        <label for="cor">Cor</label>
        <input type="text" id="cor" name="cor" required>

        <label for="preco">Preço</label>
        <input type="number" step="0.01" id="preco" name="preco" required>

        <label for="estoque">Quantidade</label>
        <input type="number" id="estoque" name="estoque" required>

        <label for="descricao">Descrição</label>
        <textarea id="descricao" name="descricao" rows="4" required></textarea>

        <!-- Campo para upload da imagem -->
        <label for="imagem">Imagem da Roupa</label>
        <input type="file" id="imagem" name="imagem" accept="image/*" required>

        <div class="button-container">
            <button type="submit">Cadastrar</button>
            <button type="reset">Limpar</button> <!-- Botão para limpar o formulário -->
        </div>
        <?php if ($mensagem != "") {
            echo "<p><strong>$mensagem</strong></p>";
        } ?>
    </form>

    
</body>

</html>
