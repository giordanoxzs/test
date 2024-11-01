<?php
session_start();
include 'conexao.php'; // Inclua a conexão com o banco de dados

// Verifica se o usuário está logado
if (!isset($_SESSION['id_usuario'])) {
    header('Location: index.php'); // Redireciona para o login se não estiver logado
    exit;
}

// Recupera o ID do usuário logado
$id_usuario = $_SESSION['id_usuario'];

// Recuperar turbinas associadas ao usuário logado
$query_turbinas = "SELECT id_turbina, nome FROM turbinas WHERE id_usuario = ?";
$stmt_turbinas = $conexao->prepare($query_turbinas);
$stmt_turbinas->bind_param("i", $id_usuario);
$stmt_turbinas->execute();
$result_turbinas = $stmt_turbinas->get_result();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_turbina = $_POST['id_turbina'];
    $data_manutencao = $_POST['data_manutencao'];
    $descricao = $_POST['descricao'];
    $custo = $_POST['custo'];

    // Insere uma nova manutenção para a turbina selecionada
    $query = "INSERT INTO manutencoes (id_turbina, data_manutencao, descricao, custo) 
              VALUES (?, ?, ?, ?)";
    $stmt = $conexao->prepare($query);
    $stmt->bind_param("issd", $id_turbina, $data_manutencao, $descricao, $custo);

    if ($stmt->execute()) {
        echo "<p>Manutenção registrada com sucesso!</p>";
    } else {
        echo "<p>Erro ao registrar manutenção: " . $stmt->error . "</p>";
    }

    // Fechar o statement
    $stmt->close();
}

// Fechar o statement de turbinas
$stmt_turbinas->close();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <link rel="stylesheet" href="css/estilos.css">
    <title>Registro de Manutenção</title>
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
            padding: 20px; /* Diminuído o padding para espaçamento */
            border-radius: 8px; /* Bordas arredondadas */
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 400px; /* Largura do card */
            text-align: center; /* Centraliza o texto dentro do card */
            display: flex; /* Flexbox para centralizar o conteúdo */
            flex-direction: column; /* Direção do conteúdo */
            align-items: center; /* Alinha os itens ao centro */
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

        select,
        input[type="date"],
        input[type="number"],
        textarea {
            width: 100%; /* Ocupa toda a largura do card */
            padding: 8px; /* Diminuído o padding */
            margin: 3px 0 10px; /* Diminuído o espaçamento */
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box; /* Inclui padding e borda na largura total */
        }

        button {
            width: 100%; /* Ocupa toda a largura do card */
            padding: 10px; /* Mantido o padding do botão */
            background-color: #5cb85c; /* Cor de fundo do botão */
            border: none;
            border-radius: 4px;
            color: white;
            font-size: 16px;
            cursor: pointer;
            margin-top: 5px; /* Diminuído o espaçamento superior */
        }

        button:hover {
            background-color: #4cae4c; /* Cor ao passar o mouse */
        }

        .btn-back {
            background-color: #007bff; /* Cor do botão de voltar */
            margin-top: 10px; /* Diminuído o espaçamento superior */
        }

        .btn-back:hover {
            background-color: #0056b3; /* Cor ao passar o mouse no botão de voltar */
        }

        p {
            text-align: center;
            color: #d9534f; /* Cor do texto de erro */
        }
    </style>
</head> 
<body>
    <div class="container"> <!-- Adicionando uma div container -->
        <h2>Registrar Manutenção</h2>
        <form method="POST">
            <label for="id_turbina">Selecione a Turbina:</label>
            <select name="id_turbina" required>
                <option value="">Escolha uma turbina</option>
                <?php while ($turbina = $result_turbinas->fetch_assoc()) { ?>
                    <option value="<?= $turbina['id_turbina'] ?>"><?= $turbina['nome'] ?></option>
                <?php } ?>
            </select>

            <label for="data_manutencao">Data:</label>
            <input type="date" name="data_manutencao" required>

            <label for="descricao">Descrição:</label>
            <textarea name="descricao" placeholder="Descrição da manutenção" required></textarea>

            <label for="custo">Custo (R$):</label>
            <input type="number" step="0.01" name="custo" placeholder="Custo" required>

            <button type="submit">Registrar</button>
            <a href="dashboard.php">
                <button type="button" class="btn-back">Voltar ao Dashboard</button>
            </a>
        </form>
    </div>
</body>
</html>
