<?php
session_start();
// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] !== 'administrador') {
    // Se não estiver logado ou não for administrador, redireciona para o login
    header("Location: index.php");
    exit;
}
// Configurações do banco de dados
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'sneakerland';

// Conexão com o banco de dados
$conn = new mysqli($host, $user, $password, $dbname);

// Verificar conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Consulta para obter os produtos mais comprados
$sql = "
    SELECT 
        p.nome AS produto,
        SUM(ip.quantidade) AS total_vendido
    FROM itens_pedido ip
    INNER JOIN produtos p ON ip.produto_id = p.id
    GROUP BY ip.produto_id
    ORDER BY total_vendido DESC
    LIMIT 10;
";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produtos Mais Comprados</title>
    <style>
       /* Estilo geral */
body {
    font-family: 'Oswald', sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f4f4f4;
    color: #333;
    line-height: 1.6;
}

/* Cabeçalho */
header {
    background-color: #6a1b9a;
    color: white;
    padding: 15px 20px;
    text-align: center;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
}

/* Título principal */
h1 {
    text-align: center;
    color: #4a148c;
    margin-top: 20px;
    font-size: 28px;
}

/* Estilo da tabela */
table {
    width: 90%;
    margin: 20px auto;
    border-collapse: collapse;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    background-color: #fff;
}

th, td {
    padding: 15px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

th {
    background-color: #6a1b9a;
    color: white;
    font-size: 18px;
    text-transform: uppercase;
}

tr:nth-child(even) {
    background-color: #f9f9f9;
}

tr:hover {
    background-color: #f1f1f1;
    cursor: default;
}

/* Responsividade */
@media (max-width: 768px) {
    table {
        font-size: 14px;
    }

    th, td {
        padding: 10px;
    }
}

/* Rodapé */
footer {
    background-color: #6a1b9a;
    color: white;
    text-align: center;
    padding: 10px;
    margin-top: 20px;
    font-size: 14px;
}

    </style>
</head>

<body>
    <?php include 'headerADM.php'; ?>
    <h1>Produtos Mais Comprados</h1>
    <table>
        <thead>
            <tr>
                <th>Produto</th>
                <th>Total Vendido</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['produto']) ?></td>
                        <td><?= $row['total_vendido'] ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="2">Nenhum dado encontrado.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>

</html>

<?php
// Fechar conexão
$conn->close();
?>