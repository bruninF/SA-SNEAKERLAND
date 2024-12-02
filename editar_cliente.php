<?php
session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] !== 'administrador') {
    header("Location: index.php");
    exit;
}

// Conexão com o banco
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sneakerland";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Verifica se o ID foi passado
if (!isset($_GET['id'])) {
    echo "ID não fornecido.";
    exit;
}

$id = intval($_GET['id']);

// Busca os dados do cliente
$sql = "SELECT id, nome, email, telefone, cpf, sexo FROM usuarios WHERE id = $id";
$result = $conn->query($sql);

if ($result->num_rows !== 1) {
    echo "Cliente não encontrado.";
    exit;
}

$cliente = $result->fetch_assoc();

// Atualiza os dados
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $cpf = $_POST['cpf'];
    $sexo = $_POST['sexo'];

    $update_sql = "UPDATE usuarios SET nome = ?, email = ?, telefone = ?, cpf = ?, sexo = ? WHERE id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("sssssi", $nome, $email, $telefone, $cpf, $sexo, $id);

    if ($stmt->execute()) {
        echo "<script>alert('Dados atualizados com sucesso!'); window.location.href = 'clientes.php';</script>";
    } else {
        echo "Erro ao atualizar os dados: " . $conn->error;
    }
}
?>

<?php include 'headerADM.php'; ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Cliente</title>
    <link rel="stylesheet" href="css/editar_cliente.css">
</head>
<body>
    <div class="container">
        <h1>Editar Cliente</h1>
        <form method="POST">
            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($cliente['nome']); ?>" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($cliente['email']); ?>" required>

            <label for="telefone">Telefone:</label>
            <input type="text" id="telefone" name="telefone" value="<?php echo htmlspecialchars($cliente['telefone']); ?>" required>

            <label for="cpf">CPF:</label>
            <input type="text" id="cpf" name="cpf" value="<?php echo htmlspecialchars($cliente['cpf']); ?>" required>

            <label for="sexo">Sexo:</label>
            <select id="sexo" name="sexo" required>
                <option value="Masculino" <?php if ($cliente['sexo'] === 'Masculino') echo 'selected'; ?>>Masculino</option>
                <option value="Feminino" <?php if ($cliente['sexo'] === 'Feminino') echo 'selected'; ?>>Feminino</option>
                <option value="Outro" <?php if ($cliente['sexo'] === 'Outro') echo 'selected'; ?>>Outro</option>
            </select>

            <button type="submit">Salvar Alterações</button>
        </form>
    </div>
</body>
</html>
