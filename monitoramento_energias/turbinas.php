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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $localizacao = $_POST['localizacao'];

    // Sanitizando a entrada para prevenir SQL Injection
    $nome = $conexao->real_escape_string($nome);
    $localizacao = $conexao->real_escape_string($localizacao);

    // Inclui o ID do usuário na consulta
    $query = "INSERT INTO turbinas (nome, localizacao, id_usuario) VALUES ('$nome', '$localizacao', '$id_usuario')";
    
    if ($conexao->query($query) === TRUE) {
        $sucesso = true; // Define a variável de sucesso
    } else {
        $erro = "Erro: " . $conexao->error; // Armazena a mensagem de erro
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <link rel="stylesheet" href="css/estilos.css">
    <title>Cadastrar Turbina</title>
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
            display: flex;
            flex-direction: column;
            align-items: center; /* Centraliza o conteúdo dentro do container */
        }

        form {
            background: white;
            padding: 30px; /* Aumenta o padding */
            border-radius: 8px; /* Arredonda as bordas do formulário */
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1); /* Sombra mais suave */
            width: 300px;
            text-align: center; /* Centraliza o texto no formulário */
        }

        h2 {
            margin-bottom: 20px;
            color: #333; /* Cor do título */
        }

        input[type="text"] {
            width: calc(100% - 20px); /* Ajusta a largura do input */
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc; /* Borda cinza claro */
            border-radius: 4px;
            font-size: 16px; /* Tamanho da fonte */
            text-align: center; /* Centraliza o texto dos inputs */
            background-color: #f9f9f9; /* Cor de fundo uniforme */
        }

        input[type="text"]:focus {
            border-color: #007bff; /* Azul mais escuro ao focar */
            outline: none; /* Remove a borda padrão do foco */
        }

        button {
            width: calc(100% - 1px); /* Ajusta a largura do botão */
            padding: 10px;
            background-color: #007bff; /* Azul mais escuro */
            border: none;
            border-radius: 4px;
            color: white;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s; /* Transição suave para o hover */
            margin-top: 10px; /* Espaço acima do botão */
        }

        button:hover {
            background-color: #0056b3; /* Azul mais escuro ao passar o mouse */
        }

        p {
            text-align: center;
            color: green;
            margin-top: 10px;
        }

        a {
            display: block;
            text-align: center;
            margin-top: 15px;
            color: #007bff; /* Azul para o link */
            text-decoration: none;
            font-weight: bold; /* Negrito para o link */
        }

        a:hover {
            text-decoration: underline;
            color: #0056b3; /* Azul mais escuro ao passar o mouse */
        }
    </style>
</head>
<body>
    <div class="container">
        <form method="POST">
            <h2>Cadastrar Turbina</h2>
            <input type="text" name="nome" placeholder="Nome da Turbina" required>
            <input type="text" name="localizacao" placeholder="Localização" required>
            <button type="submit">Cadastrar</button>

            <!-- Mensagem de sucesso ou erro -->
            <?php if (isset($sucesso)): ?>
                <p>Turbina cadastrada com sucesso!</p>
            <?php elseif (isset($erro)): ?>
                <p style="color: red;"><?php echo $erro; ?></p>
            <?php endif; ?>
        </form>
        
        <a href="dashboard.php">Voltar ao Dashboard</a>
    </div>
</body>
</html>
