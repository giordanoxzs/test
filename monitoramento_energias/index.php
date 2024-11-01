<?php
session_start();
include 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['usuario'];
    $senha = $_POST['senha'];

    $nome = $conexao->real_escape_string($nome);
    $senha = $conexao->real_escape_string($senha);

    $query = "SELECT id_usuario, nome FROM usuarios WHERE nome = '$nome' AND senha = '$senha'";
    $resultado = $conexao->query($query);

    if ($resultado->num_rows > 0) {
        $usuario = $resultado->fetch_assoc();
        $_SESSION['usuario'] = $usuario['nome'];
        $_SESSION['id_usuario'] = $usuario['id_usuario'];
        header('Location: dashboard.php');
        exit;
    } else {
        $erro = "Usuário ou senha incorretos.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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
    <div class="fade"></div> <!-- Adicionando o fade -->
    <div class="bg-white dark:bg-gray-900 shadow-md rounded-lg px-8 py-6" style="width: 400px; z-index: 10;">
        <h1 class="text-3xl font-bold text-center mb-4 dark:text-gray-200">Bem-vindo de Volta!</h1>
        <form method="POST">
            <?php if (isset($erro)): ?>
                <p class="text-red-500 text-sm mb-4 text-center"><?= htmlspecialchars($erro) ?></p>
            <?php endif; ?>
            <div class="mb-4">
                <label for="usuario" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Usuário</label>
                <input type="text" id="usuario" name="usuario" class="shadow-sm rounded-md w-full px-3 py-3 border border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="Digite seu usuário" required>
            </div>
            <div class="mb-4">
                <label for="senha" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Senha</label>
                <input type="password" id="senha" name="senha" class="shadow-sm rounded-md w-full px-3 py-3 border border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="Digite sua senha" required>
            </div>
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <input type="checkbox" id="remember" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" checked>
                    <label for="remember" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">Lembrar-me</label>
                </div>
            </div>
            <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-lg font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Entrar</button>
        </form>

        <!-- Mensagem para criar conta -->
        <p class="text-center text-gray-700 dark:text-gray-300 mt-4">Não possui uma conta? <a href="cadastro.php" class="text-indigo-500 hover:text-indigo-700">Cadastre-se aqui</a></p>
    </div>
</body>
</html>
