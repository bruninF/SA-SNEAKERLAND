<?php
session_start();

// Configurações de conexão com o banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sneakerland";

// Cria a conexão com o banco de dados
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica a conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

$cadastro_sucesso = false;
$tipo_usuario_cadastrado = "cliente"; // Tipo padrão para cliente
$usuario_error = ""; // Variável para mensagens de erro de usuário
$erro = ""; // Mensagens de erro

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $senha = $_POST['senha'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $cpf = $_POST['cpf'];
    $sexo = $_POST['sexo'];

    // Verifica se o checkbox de admin ou funcionário foi marcado
    if (isset($_POST['admin']) && $_POST['admin'] == 'on') {
        if ($_POST['senha_funcionario'] !== "1234") {
            $erro = "Senha incorreta para administrador.";
        } else {
            $tipo_usuario_cadastrado = "administrador"; // Define o tipo de usuário
        }
    } elseif (isset($_POST['funcionario']) && $_POST['funcionario'] == 'on') {
        if ($_POST['senha_funcionario'] !== "1234") {
            $erro = "Senha incorreta para funcionário.";
        } else {
            $tipo_usuario_cadastrado = "funcionario"; // Define o tipo de usuário
        }
    }

    // Só continuar se não houver erros
    if (empty($erro)) {
        // Verifica se o email já existe
        $sql_check_user = "SELECT * FROM usuarios WHERE email = '$email'";
        $result = $conn->query($sql_check_user);

        if ($result->num_rows > 0) {
            $usuario_error = "E-mail já cadastrado.";
        } else {
            // Insere o novo usuário no banco de dados
            $sql_insert = "INSERT INTO usuarios (nome, email, senha, telefone, cpf, sexo, tipo_usuario) 
                           VALUES ('$nome', '$email', '$senha', '$telefone', '$cpf', '$sexo', '$tipo_usuario_cadastrado')";

            if ($conn->query($sql_insert) === TRUE) {
                // Se for funcionário, também insere na tabela Funcionarios
                if ($tipo_usuario_cadastrado == "funcionario") {
                    $usuario_id = $conn->insert_id; // ID do usuário recém-criado
                    $cargo = $_POST['cargo'];
                    $data_admissao = $_POST['data_admissao'];
                    $salario = $_POST['salario'];

                    $sql_funcionario = "INSERT INTO funcionarios (usuario_id, cargo, data_admissao, salario) 
                                        VALUES ('$usuario_id', '$cargo', '$data_admissao', '$salario')";
                    $conn->query($sql_funcionario);
                }

                $cadastro_sucesso = true;

                // Redireciona para a página correta
                if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] !== 'administrador') {
                    header("Location: index.php");
                } else {
                    header("Location: HomeADM.php"); // Administrador ou Funcionário
                }
                exit(); // Para garantir que o script não continue executando
            } else {
                $erro = "Erro ao cadastrar: " . $conn->error;
            }
        }
    }
}

$conn->close();
?>
    <?php if (isset($_SESSION['usuario_id'])): ?>
        <?php include 'headerADM.php'; ?>
    <?php endif; ?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - Sneakerland</title>
    <link rel="stylesheet" href="css/cadastro.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
</head>

<body>
    
    <div class="registration-container">
        <h1>Cadastro</h1>
        <h2 id="tipoUsuarioSelecionado">Usuário</h2>

        <form action="cadastro.php" method="POST" id="registrationForm">
            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" required>

            <label for="email">E-mail:</label>
            <input type="email" id="email" name="email" required>
            <?php if (!empty($usuario_error)): ?>
                <p style="color: red;"><?php echo $usuario_error; ?></p>
            <?php endif; ?>

            <label for="senha">Senha:</label>
            <input type="password" id="senha" name="senha" required minlength="8">

            <label for="telefone">Telefone:</label>
            <input type="text" id="telefone" name="telefone" required>

            <label for="cpf">CPF:</label>
            <input type="text" id="cpf" name="cpf" required>

            <label for="sexo">Sexo:</label>
            <select id="sexo" name="sexo" required>
                <option value="">Selecione</option>
                <option value="masculino">Masculino</option>
                <option value="feminino">Feminino</option>
            </select>

            <?php if (isset($_SESSION['usuario_id'])): ?>
                <!-- Exibe apenas para administradores -->
                <div class="checkbox-container">
                    <label>
                        <input type="checkbox" name="admin" id="admin" onclick="toggleCheckbox(this)"> Administrador
                    </label>
                    <label>
                        <input type="checkbox" name="funcionario" id="funcionario" onclick="toggleCheckbox(this)"> Funcionário
                    </label>
                </div>

                <div id="senha_funcionario_container" style="display: none;">
                    <label for="senha_funcionario">Senha para Cadastrar Funcionário/Administrador:</label>
                    <input type="password" id="senha_funcionario" name="senha_funcionario">
                </div>

                <div id="dados_funcionario" style="display: none;">
                    <label for="cargo">Cargo:</label>
                    <input type="text" id="cargo" name="cargo">

                    <label for="data_admissao">Data de Admissão:</label>
                    <input type="date" id="data_admissao" name="data_admissao">

                    <label for="salario">Salário:</label>
                    <input type="text" id="salario" name="salario">
                </div>
            <?php endif; ?>

            <button type="submit">Cadastrar</button>
        </form>
    </div>

    <script>
        $(document).ready(function() {
            // Aplica máscara de telefone
            $('#telefone').mask('(00) 00000-0000');

            // Aplica máscara de CPF
            $('#cpf').mask('000.000.000-00');

            // Aplica máscara de salário (formato de moeda)
            $('#salario').mask('000.000.000.000.000,00', {
                reverse: true
            });
        });

        function toggleCheckbox(checkbox) {
            // Desmarca os outros checkboxes
            if (checkbox.id === "admin") {
                document.getElementById("funcionario").checked = false;
                document.getElementById("tipoUsuarioSelecionado").textContent = "Administrador"; // Atualiza o texto para Administrador
            } else if (checkbox.id === "funcionario") {
                document.getElementById("admin").checked = false;
                document.getElementById("tipoUsuarioSelecionado").textContent = "Funcionário"; // Atualiza o texto para Funcionário
            }

            // Exibe ou oculta o campo de senha para funcionário/admin
            const senhaContainer = document.getElementById("senha_funcionario_container");
            const dadosFuncionario = document.getElementById("dados_funcionario");
            if (document.getElementById("admin").checked || document.getElementById("funcionario").checked) {
                senhaContainer.style.display = "block";
                if (document.getElementById("funcionario").checked) {
                    dadosFuncionario.style.display = "block"; // Exibe dados do funcionário
                } else {
                    dadosFuncionario.style.display = "none"; // Oculta dados do funcionário
                }
            } else {
                senhaContainer.style.display = "none";
                dadosFuncionario.style.display = "none"; // Oculta dados do funcionário
                document.getElementById("tipoUsuarioSelecionado").textContent = "Usuário"; // Retorna o texto para Usuário
            }
        }

        document.getElementById("registrationForm").onsubmit = function() {
            const senha = document.getElementById("senha").value;
            const senhaRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/;

            // Verifica se a senha é forte
            if (!senhaRegex.test(senha)) {
                alert("A senha deve ter pelo menos 8 caracteres, com uma letra maiúscula, uma letra minúscula e um número.");
                return false; // Cancela o envio se a senha não for forte o suficiente
            }

            return true; // Permite o envio se a validação estiver correta
        };

        
    </script>
</body>

</html>