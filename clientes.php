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

// Cria conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Consulta SQL para buscar clientes (usuários com tipo 'cliente')
$sql = "SELECT id, nome, email, telefone, cpf, sexo FROM usuarios WHERE tipo_usuario = 'cliente'";
$result = $conn->query($sql);

?>
<?php include 'headerADM.php'; ?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientes - Nível 3</title>
    <link rel="stylesheet" href="css/clientes.css">
</head>

<body>
    
    <div class="container">
        <h1>Clientes</h1>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Telefone</th>
                    <th>CPF</th>
                    <th>Sexo</th>
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
                                <td>" . $row["email"] . "</td>
                                <td>" . $row["telefone"] . "</td>
                                <td>" . $row["cpf"] . "</td>
                                <td>" . $row["sexo"] . "</td>
                                <td>
                                    <form method='POST' action='remover_cliente.php' onsubmit='return confirm(\"Tem certeza que deseja remover este cliente?\");'>
                                        <input type='hidden' name='id' value='" . $row["id"] . "' />
                                        <div class='button-container'>
                                        <button type='submit'>Excluir</button>
                                        </div>
                                    </form>
                                </td>
                            </tr>";
                    }
                } else {
                    echo "<tr><td colspan='8'>Nenhum cliente encontrado</td></tr>";
                }
                $conn->close();
                ?>
            </tbody>
        </table>

        
    </div>
</body>

</html>