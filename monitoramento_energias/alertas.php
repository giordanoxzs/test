<?php
session_start(); // Inicie a sessão
include 'conexao.php'; // Inclua a conexão com o banco de dados

// Verifique se o usuário está logado
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php"); // Redirecione para a página de login se não estiver logado
    exit();
}

$id_usuario = $_SESSION['id_usuario']; // Armazene o id_usuario da sessão

// Inserir novo alerta se o formulário for enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_turbina = $_POST['id_turbina'];
    $data_hora = $_POST['data_hora'];
    $tipo_alerta = $_POST['tipo_alerta']; // Agora representa o nível de alerta
    $descricao = $_POST['descricao'];

    $query_inserir = "INSERT INTO alertas (id_turbina, data_hora, tipo_alerta, descricao, id_usuario)
                      VALUES (?, ?, ?, ?, ?)";

    $stmt_inserir = $conexao->prepare($query_inserir);
    $stmt_inserir->bind_param("ssssi", $id_turbina, $data_hora, $tipo_alerta, $descricao, $id_usuario);

    if ($stmt_inserir->execute()) {
        echo "<p class='msg-success'>Alerta registrado com sucesso!</p>";
    } else {
        echo "<p class='msg-error'>Erro ao registrar alerta: " . $conexao->error . "</p>";
    }
}

// Consulta para obter dados de alertas com nome da turbina
$query_alertas = "
    SELECT alertas.id_turbina, alertas.data_hora, alertas.tipo_alerta, alertas.descricao, turbinas.nome AS nome_turbina 
    FROM alertas 
    JOIN turbinas ON alertas.id_turbina = turbinas.id_turbina
    WHERE alertas.id_usuario = ?";
$stmt_alertas = $conexao->prepare($query_alertas);
$stmt_alertas->bind_param("i", $id_usuario);
$stmt_alertas->execute();
$alertas = $stmt_alertas->get_result();

// Consulta para obter apenas turbinas associadas ao usuário logado
$query_turbinas = "SELECT id_turbina, nome FROM turbinas WHERE id_usuario = ?";
$stmt_turbinas = $conexao->prepare($query_turbinas);
$stmt_turbinas->bind_param("i", $id_usuario);
$stmt_turbinas->execute();
$turbinas = $stmt_turbinas->get_result();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <link rel="stylesheet" href="css/estilos.css">
    <title>Alertas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('imagens/wallpaper2.jpg');
            background-size: cover;
            background-position: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }
        h2 {
            color: #333;
            margin-bottom: 20px;
            text-align: center;
        }
        form, .table-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px;
            margin-bottom: 20px;
        }
        label {
            font-weight: bold;
            margin-top: 10px;
            display: block;
            color: #555;
        }
        input, select {
            width: 100%;
            padding: 12px;
            margin: 5px 0 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            transition: border-color 0.3s;
        }
        input[type="datetime-local"] {
            width: 96%;
            padding: 12px;
            margin: 5px 0 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            transition: border-color 0.3s;
        }
        input:focus, select:focus {
            border-color: #5cb85c;
            outline: none;
        }
        textarea {
            width: 96%;
            padding: 12px;
            margin: 5px 0 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            transition: border-color 0.3s;
        }
        textarea:focus {
            border-color: #5cb85c;
            outline: none;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        button {
            padding: 12px 24px;
            background-color: #5cb85c;
            border: none;
            border-radius: 4px;
            color: white;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s;
        }
        button:hover {
            background-color: #4cae4c;
            transform: translateY(-2px);
        }
        .btn-voltar {
            margin-top: 10px;
            background-color: #007bff;
        }
        .btn-voltar:hover {
            background-color: #0056b3;
        }
        .msg-success {
            color: #28a745;
            font-weight: bold;
            margin-top: 10px;
            text-align: center;
        }
        .msg-error {
            color: #dc3545;
            font-weight: bold;
            margin-top: 10px;
            text-align: center;
        }
    </style>
</head>
<body>
    <h2>Registrar Novo Alerta</h2>
    <form method="POST">
        <label for="id_turbina">Selecionar Turbina:</label>
        <select name="id_turbina" id="id_turbina" required>
            <option value="">Selecione uma turbina</option>
            <?php while ($turbina = $turbinas->fetch_assoc()): ?>
                <option value="<?= htmlspecialchars($turbina['id_turbina']) ?>"><?= htmlspecialchars($turbina['nome']) ?></option>
            <?php endwhile; ?>
        </select>

        <label for="data_hora">Data e Hora:</label>
        <input type="datetime-local" name="data_hora" id="data_hora" required>

        <label for="tipo_alerta">Tipo de Alerta:</label>
        <select name="tipo_alerta" id="tipo_alerta" required>
            <option value="">Selecione um nível de alerta</option>
            <option value="Baixo">Baixo</option>
            <option value="Médio">Médio</option>
            <option value="Alto">Alto</option>
        </select>

        <label for="descricao">Descrição:</label>
        <textarea name="descricao" id="descricao" rows="4" required></textarea>

        <button type="submit">Registrar Alerta</button>
    </form>

    <div class="table-container">
        <h2>Alertas Registrados</h2>
        <?php if ($alertas && $alertas->num_rows > 0): ?>
            <table>
                <tr>
                    <th>Nome da Turbina</th> <!-- Exibe o nome da turbina -->
                    <th>Data e Hora</th>
                    <th>Nível de Alerta</th>
                    <th>Descrição</th>
                </tr>
                <?php while ($alerta = $alertas->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($alerta['nome_turbina']) ?></td> <!-- Nome da turbina -->
                        <td><?= htmlspecialchars($alerta['data_hora']) ?></td>
                        <td><?= htmlspecialchars($alerta['tipo_alerta']) ?></td>
                        <td><?= htmlspecialchars($alerta['descricao']) ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p>Nenhum alerta encontrado.</p>
        <?php endif; ?>
    </div>

    <form action="dashboard.php" method="GET">
        <button type="submit" class="btn-voltar">Voltar para a Tela Principal</button>
    </form>
</body>
</html>