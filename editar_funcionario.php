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

// Busca os dados do funcionário
$sql = "SELECT u.id, u.nome, u.email, u.senha, u.telefone, u.cpf, u.sexo, f.cargo, f.data_admissao, f.salario 
        FROM usuarios u 
        JOIN funcionarios f ON u.id = f.usuario_id 
        WHERE u.id = $id";

$result = $conn->query($sql);

if ($result->num_rows !== 1) {
    echo "Funcionário não encontrado.";
    exit;
}

$funcionario = $result->fetch_assoc();

// Atualiza os dados
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $telefone = $_POST['telefone'];
    $cpf = $_POST['cpf'];
    $sexo = $_POST['sexo'];
    $cargo = $_POST['cargo'];
    $data_admissao = $_POST['data_admissao'];
    $salario = $_POST['salario'];

    // Atualiza os dados nas tabelas
    $update_usuarios = "UPDATE usuarios SET nome = ?, email = ?, senha = ?, telefone = ?, cpf = ?, sexo = ? WHERE id = ?";
    $stmt_usuarios = $conn->prepare($update_usuarios);
    $stmt_usuarios->bind_param("ssssssi", $nome, $email, $senha, $telefone, $cpf, $sexo, $id);

    $update_funcionarios = "UPDATE funcionarios SET cargo = ?, data_admissao = ?, salario = ? WHERE usuario_id = ?";
    $stmt_funcionarios = $conn->prepare($update_funcionarios);
    $stmt_funcionarios->bind_param("ssdi", $cargo, $data_admissao, $salario, $id);

    if ($stmt_usuarios->execute() && $stmt_funcionarios->execute()) {
        echo "<script>alert('Dados atualizados com sucesso!'); window.location.href = 'funcionarios.php';</script>";
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
    <title>Editar Funcionário</title>
    <link rel="stylesheet" href="css/editar_funcionario.css">
</head>
<body>
    <div class="container">
        <h1>Editar Funcionário</h1>
        <form method="POST">
            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($funcionario['nome']); ?>" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($funcionario['email']); ?>" required>

            <label for="senha">Senha:</label>
            <input type="text" id="senha" name="senha" value="<?php echo htmlspecialchars($funcionario['senha']); ?>" required>

            <label for="telefone">Telefone:</label>
            <input type="text" id="telefone" name="telefone" value="<?php echo htmlspecialchars($funcionario['telefone']); ?>" required>

            <label for="cpf">CPF:</label>
            <input type="text" id="cpf" name="cpf" value="<?php echo htmlspecialchars($funcionario['cpf']); ?>" required>

            <label for="sexo">Sexo:</label>
            <select id="sexo" name="sexo" required>
                <option value="Masculino" <?php if ($funcionario['sexo'] === 'Masculino') echo 'selected'; ?>>Masculino</option>
                <option value="Feminino" <?php if ($funcionario['sexo'] === 'Feminino') echo 'selected'; ?>>Feminino</option>
                <option value="Outro" <?php if ($funcionario['sexo'] === 'Outro') echo 'selected'; ?>>Outro</option>
            </select>

            <label for="cargo">Cargo:</label>
            <input type="text" id="cargo" name="cargo" value="<?php echo htmlspecialchars($funcionario['cargo']); ?>" required>

            <label for="data_admissao">Data de Admissão:</label>
            <input type="date" id="data_admissao" name="data_admissao" value="<?php echo htmlspecialchars($funcionario['data_admissao']); ?>" required>

            <label for="salario">Salário:</label>
            <input type="number" id="salario" name="salario" step="0.01" value="<?php echo htmlspecialchars($funcionario['salario']); ?>" required>

            <button type="submit">Salvar Alterações</button>
        </form>
    </div>
</body>
</html>
