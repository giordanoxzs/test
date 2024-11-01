<?php
include 'conexao.php';

$mensagemSucesso = ""; // Variável para armazenar mensagens de sucesso

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['usuario']; // Alterando de 'usuario' para 'nome'
    $senha = $_POST['senha'];

    // Sanitizando entrada para prevenir SQL Injection
    $nome = $conexao->real_escape_string($nome);
    $senha = $conexao->real_escape_string($senha);

    // Corrigindo a consulta SQL para usar 'nome' no lugar de 'usuario'
    $query = "INSERT INTO usuarios (nome, senha) VALUES ('$nome', '$senha')";
    
    if ($conexao->query($query) === TRUE) {
        $mensagemSucesso = "Usuário cadastrado com sucesso."; // Mensagem de sucesso
        header('Location: index.php'); // Redirecionando para index.php
        exit; // Adicionando exit para garantir que o script não continue após o redirecionamento
    } else {
        echo "Erro: " . $conexao->error;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Usuário</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('imagens/wallpaper.jpg'); /* Adicione o caminho da sua imagem */
            background-size: cover; /* Faz a imagem cobrir toda a tela */
            background-position: center; /* Centraliza a imagem */
        }
        .fade {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5); /* Efeito de fade preto */
            z-index: 0; /* Colocando o fade atrás do conteúdo */
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center w-full">
    <div class="fade"></div>
    <div class="bg-white dark:bg-gray-900 shadow-md rounded-lg px-8 py-6" style="width: 400px; z-index: 10;">
        <h2 class="text-3xl font-bold text-center mb-4 dark:text-gray-200">Cadastro</h2>
        <form method="POST">
            <div class="mb-4">
                <label for="usuario" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Usuário</label>
                <input type="text" id="usuario" name="usuario" class="shadow-sm rounded-md w-full px-3 py-3 border border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="Digite seu usuário" required>
            </div>
            <div class="mb-4">
                <label for="senha" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Senha</label>
                <input type="password" id="senha" name="senha" class="shadow-sm rounded-md w-full px-3 py-3 border border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="Digite sua senha" required>
            </div>
            <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-lg font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Cadastrar</button>
            
            <!-- Exibindo a mensagem de sucesso, se houver -->
            <?php if ($mensagemSucesso): ?>
                <p class="text-green-500 text-sm mt-4 text-center"><?= htmlspecialchars($mensagemSucesso) ?></p>
            <?php endif; ?>
        </form>

        <!-- Link para voltar para a tela de login -->
        <p class="text-center text-gray-700 dark:text-gray-300 mt-4">Já possui login? <a href="index.php" class="text-indigo-500 hover:text-indigo-700">Login aqui</a></p>
    </div>
</body>
</html>
