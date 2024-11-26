<?php
session_start(); // Inicia a sessão

// Conexão com o banco de dados MySQL
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sneakerland";

// Cria a conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica a conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email']; // Coleta o email do formulário
    $senha = $_POST['senha']; // Coleta a senha do formulário

    // Previne SQL Injection
    $email = $conn->real_escape_string($email);
    $senha = $conn->real_escape_string($senha);

    // Consulta no banco de dados pelo email
    $sql = "SELECT * FROM usuarios WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Verificação da senha (alterar caso esteja criptografada)
        if ($senha == $row['senha']) { // Se a senha for em texto plano
            // Salva os dados na sessão
            $_SESSION['usuario_id'] = $row['id']; // Armazena o ID do usuário na sessão
            $_SESSION['tipo_usuario'] = $row['tipo_usuario']; // Armazena o tipo de usuário na sessão
            $_SESSION['nome_usuario'] = $row['nome']; // Armazena o nome do usuário na sessão

            // Redireciona com base no nível de acesso
            if ($row['tipo_usuario'] == 'administrador') {
                header("Location: HomeADM.php");
            } elseif ($row['tipo_usuario'] == 'cliente') {
                header("Location: HomeUsuario.php");
            } elseif ($row['tipo_usuario'] == 'funcionario') {
                header("Location: HomeFuncionario.php");
            }
            exit; // Garante que o script seja encerrado após o redirecionamento
        } else {
            $erro_login = "Senha incorreta."; // Mensagem de erro se a senha não confere
        }
    } else {
        $erro_login = "Usuário não encontrado."; // Mensagem de erro se o email não existir
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sneakerland</title>
    <link rel="stylesheet" href="css/index.css"> <!-- Link para o CSS -->
</head>
<body>
    <div class="login-container">
        <h1>Login</h1>

        <?php
        if (isset($erro_login)) {
            echo "<p style='color:red;'>$erro_login</p>";
        }
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
            <label for="email">Email</label>
            <input type="text" id="email" name="email" required>

            <label for="senha">Senha</label>
            <input type="password" id="senha" name="senha" required>

            <button type="submit">Entrar</button>
        </form>

        <p class="signup-prompt">Se não tiver login, <a href="cadastro.php" class="signup-link">clique aqui para se cadastrar</a>.</p>
    </div>
</body>
</html>
