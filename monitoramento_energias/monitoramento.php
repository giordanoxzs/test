<?php
session_start();
include 'conexao.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['id_usuario'])) {
    header('Location: index.php');
    exit;
}

// Recupera o ID do usuário logado
$id_usuario = $_SESSION['id_usuario'];

// Recuperar turbinas pertencentes ao usuário logado para exibição no campo de seleção
$query_turbinas = "SELECT id_turbina, nome FROM turbinas WHERE id_usuario = '$id_usuario'";
$turbinas = $conexao->query($query_turbinas);

// Inicializa a mensagem de sucesso ou erro
$message = "";

// Se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_turbina = $_POST['id_turbina'];
    $data_hora = $_POST['data_hora'];
    $energia_gerada = $_POST['energia_gerada'];
    $status = $_POST['status'];

    // Preparar a consulta SQL para inserção
    $query_insert = "INSERT INTO monitoramento (id_turbina, data_hora, energia_gerada, status, id_usuario) 
                     VALUES ('$id_turbina', '$data_hora', '$energia_gerada', '$status', '$id_usuario')";

    // Executa a consulta
    if ($conexao->query($query_insert) === TRUE) {
        $message = "<p class='success'>Dados registrados com sucesso!</p>"; // Mensagem de sucesso
    } else {
        $message = "<p class='error'>Erro ao registrar dados: " . $conexao->error . "</p>"; // Mensagem de erro
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <link rel="stylesheet" href="css/estilos.css">
    <title>Monitoramento de Desempenho</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('imagens/wallpaper2.jpg'); /* Adicione o caminho da sua imagem */
            background-size: cover; /* Faz a imagem cobrir toda a tela */
            background-position: center; /* Centraliza a imagem */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            background: white;
            padding: 20px; /* Diminuído o padding */
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 400px;
            text-align: center;
        }

        h2 {
            margin-bottom: 10px; /* Diminuído o espaçamento inferior */
            color: #333;
        }

        label {
            margin-top: 5px; /* Diminuído o espaçamento superior */
            display: block;
            font-weight: bold;
        }

        .pergunta {
            margin-bottom: 10px; /* Diminuído o espaçamento inferior */
            font-weight: bold;
        }

        select,
        input[type="text"],
        input[type="number"],
        input[type="datetime-local"] {
            width: 100%;
            padding: 8px; /* Diminuído o padding */
            margin: 3px 0 10px; /* Diminuído o espaçamento */
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #5cb85c;
            border: none;
            border-radius: 4px;
            color: white;
            font-size: 16px;
            cursor: pointer;
            margin-top: 5px; /* Diminuído o espaçamento superior */
        }

        button:hover {
            background-color: #4cae4c;
        }

        .btn-voltar {
            background-color: #007bff;
            margin-top: 10px; /* Diminuído o espaçamento superior */
        }

        .btn-voltar:hover {
            background-color: #0056b3;
        }

        .success {
            color: #5cb85c; /* Cor para a mensagem de sucesso */
            text-align: center;
            margin-bottom: 10px; /* Margem inferior para separação */
        }

        .error {
            color: #d9534f; /* Cor para a mensagem de erro */
            text-align: center;
            margin-bottom: 10px; /* Margem inferior para separação */
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Monitoramento de Desempenho</h2>
        <?= $message; ?> <!-- Exibe a mensagem de sucesso ou erro -->

        <form method="POST">
            <label for="id_turbina" class="pergunta">Selecione a Turbina:</label>
            <select name="id_turbina" required>
                <option value="">Escolha uma turbina</option>
                <?php while ($turbina = $turbinas->fetch_assoc()) { ?>
                    <option value="<?= $turbina['id_turbina'] ?>"><?= htmlspecialchars($turbina['nome']) ?></option>
                <?php } ?>
            </select>

            <label for="data_hora" class="pergunta">Data e Hora:</label>
            <input type="datetime-local" name="data_hora" required>

            <label for="energia_gerada" class="pergunta">Energia Gerada (kWh):</label>
            <input type="number" step="0.01" name="energia_gerada" required>

            <label for="status" class="pergunta">Status:</label>
            <select name="status" required>
                <option value="Planejamento">Planejamento</option>
                <option value="Em Andamento">Em Andamento</option>
                <option value="Finalizado">Finalizado</option>
            </select>

            <button type="submit">Registrar Dados</button>
        </form>

        <form action="dashboard.php" method="GET">
            <button type="submit" class="btn-voltar">Voltar para Dashboard</button>
        </form>
    </div>
</body>
</html>
