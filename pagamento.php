<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] !== 'cliente') {
    // Se não estiver logado ou não for administrador, redireciona para o login
    header("Location: index.php");
    exit;
}
$conn = new mysqli("localhost", "root", "", "sneakerland");

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

$usuario_id = $_SESSION['usuario_id'];
$sqlEndereco = "SELECT * FROM enderecos_entrega WHERE usuario_id = ?";
$stmtEndereco = $conn->prepare($sqlEndereco);
$stmtEndereco->bind_param("i", $usuario_id);
$stmtEndereco->execute();
$resultEndereco = $stmtEndereco->get_result();
$enderecoExistente = $resultEndereco->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página de Pagamento - Sneakerland</title>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/pagamento.css">
</head>
<body>
    <?php include 'header.php'; ?>
    
    <form id="paymentForm" method="post" action="processar_compra.php">
        <h2>Endereço de Entrega</h2>
        <input type="hidden" name="usuario_id" value="<?php echo $usuario_id; ?>">
        
        <label for="endereco">Endereço:</label>
        <input type="text" id="endereco" name="endereco" value="<?php echo $enderecoExistente['endereco'] ?? ''; ?>" required>

        <label for="cidade">Cidade:</label>
        <input type="text" id="cidade" name="cidade" value="<?php echo $enderecoExistente['cidade'] ?? ''; ?>" required readonly>

        <label for="estado">Estado:</label>
        <input type="text" id="estado" name="estado" value="<?php echo $enderecoExistente['estado'] ?? ''; ?>" required readonly>

        <label for="cep">CEP:</label>
        <input type="text" id="cep" name="cep" value="<?php echo $enderecoExistente['cep'] ?? ''; ?>" required maxlength="10" onblur="buscarEndereco()">

        <div class="payment-options">
            <h3>Escolha a forma de pagamento</h3>
            <label>
                <input type="radio" name="payment_method" value="credit-debit" onclick="toggleCardForm()" required> Cartão de Crédito/Débito
            </label>
            <label>
                <input type="radio" name="payment_method" value="pix" onclick="toggleCardForm()" required> Pix
            </label>
        </div>

        <div class="pix-info" id="pix-info" style="display: none;">
            <h3>Dados para pagamento via PIX</h3>
            <p>Chave PIX: <strong>SNEAKERLAND@gmail.com</strong></p>
        </div>

        <div class="card-form" id="card-form" style="display: none;">
            <h3>Dados do Cartão</h3>
            <input type="text" placeholder="Nome no Cartão" name="card_name" id="card-name">
            <input type="text" placeholder="Número do Cartão" name="card_number" id="card-number" maxlength="19">
            <input type="text" placeholder="Validade (MM/AA)" name="card_expiry" id="card-expiry" maxlength="5">
            <input type="text" placeholder="CVV" name="card_cvv" id="card-cvv" maxlength="3">
        </div>

        <button type="submit" name="finalizar_compra" class="finalize-btn">Finalizar Compra</button>
    </form>

    <script>
        function buscarEndereco() {
            const cep = document.getElementById('cep').value.replace(/\D/g, '');

            if (cep.length === 8) {
                fetch(`https://viacep.com.br/ws/${cep}/json/`)
                    .then(response => response.json())
                    .then(data => {
                        if (!data.erro) {
                            document.getElementById('cidade').value = data.localidade;
                            document.getElementById('estado').value = data.uf;
                        } else {
                            alert('CEP não encontrado.');
                        }
                    })
                    .catch(error => {
                        console.error('Erro ao buscar o endereço:', error);
                        alert('Erro ao buscar o endereço. Tente novamente.');
                    });
            } else {
                alert('CEP inválido.');
            }
        }

        function toggleCardForm() {
            const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;
            const cardForm = document.getElementById('card-form');
            const pixInfo = document.getElementById('pix-info');
            const cardFields = document.querySelectorAll('#card-form input');

            cardForm.style.display = 'none';
            pixInfo.style.display = 'none';

            if (paymentMethod === 'credit-debit') {
                cardForm.style.display = 'block';
                cardFields.forEach(field => field.required = true); // Habilita os campos do cartão
            } else if (paymentMethod === 'pix') {
                pixInfo.style.display = 'block';
                cardFields.forEach(field => field.required = false); // Desabilita os campos do cartão
            }
        }

        document.getElementById('card-number').addEventListener('input', function(e) {
            e.target.value = e.target.value
                .replace(/\D/g, '')
                .replace(/(\d{4})(?=\d)/g, '$1 ')
                .trim();
        });

        document.getElementById('card-expiry').addEventListener('input', function(e) {
            e.target.value = e.target.value
                .replace(/\D/g, '')
                .replace(/(\d{2})(\d{1,2})/, '$1/$2')
                .slice(0, 5);
        });

    </script>
</body>
</html>
