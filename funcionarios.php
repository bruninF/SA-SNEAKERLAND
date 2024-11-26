<?php
session_start(); // Iniciar a sessão

if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] !== 'administrador') {
    // Se não estiver logado ou não for administrador, redireciona para o login
    header("Location: index.php");
    exit;
}
// Conexão com o banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sneakerland";

// Conexão com o banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sneakerland";

// Cria conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Consulta SQL para buscar funcionários
$sql = "SELECT u.id, u.nome, u.email, u.senha, u.telefone, u.cpf, u.sexo, f.cargo, f.data_admissao, f.salario 
        FROM usuarios u 
        JOIN funcionarios f ON u.id = f.usuario_id
        WHERE u.tipo_usuario = 'funcionario'";

$result = $conn->query($sql);

?>
<?php include 'headerADM.php'; ?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Funcionários</title>
    <link rel="stylesheet" href="css/clientes.css">
</head>

<body>


    <div class="container">
        <h1>Funcionários</h1>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Senha</th>
                    <th>Email</th>
                    <th>Telefone</th>
                    <th>CPF</th>
                    <th>Sexo</th>
                    <th>Cargo</th>
                    <th>Data de Admissão</th>
                    <th>Salário</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    // Exibe dados de cada linha
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>" . $row["id"] . "</td>
                                <td>" . $row["nome"] . "</td>
                                <td>" . $row["senha"] . "</td>
                                <td>" . $row["email"] . "</td>
                                <td>" . $row["telefone"] . "</td>
                                <td>" . $row["cpf"] . "</td>
                                <td>" . $row["sexo"] . "</td>
                                <td>" . $row["cargo"] . "</td>
                                <td>" . $row["data_admissao"] . "</td>
                                <td>" . $row["salario"] . "</td>
                                <td>
                                    <form method='POST' action='demitir.php' onsubmit='return confirm(\"Tem certeza que deseja demitir?\");'>
                                        <input type='hidden' name='id' value='" . $row["id"] . "' />
                                        <button type='submit'>Demitir</button>
                                    </form>
                                </td>
                            </tr>";
                    }
                } else {
                    echo "<tr><td colspan='11'>Nenhum funcionário encontrado</td></tr>";
                }
                $conn->close();
                ?>
            </tbody>
        </table>


    </div>

</body>

</html>