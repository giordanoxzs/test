<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <link rel="stylesheet" href="css/estilos.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- Adicionando Font Awesome -->
    <title>Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('imagens/wallpaper3.jpg'); /* Adicione o caminho da sua imagem */
            background-size: cover; /* Faz a imagem cobrir toda a tela */
            background-position: center; /* Centraliza horizontalmente e move 20% para baixo */
            display: flex;
            flex-direction: column;
            align-items: center; /* Centraliza horizontalmente */
            justify-content: flex-start; /* Alinha os itens ao topo */
            padding: 20px;
            position: relative; /* Para permitir o posicionamento absoluto do botão de logout */
            height: 100vh; /* Garante que o body ocupe toda a altura da tela */
            overflow: hidden; /* Oculta a rolagem */
        }
        h1 {
            color: #FFFF;
            margin-bottom: 10px; /* Diminui a margem inferior para o título */
            text-align: center; /* Centraliza o título */
            margin-top: 20px; /* Adiciona uma margem superior para afastar do topo */
            position: relative; /* Para que o z-index funcione corretamente */
            z-index: 1; /* Garante que o título fique acima do fade */
        }
        .btn-logout {
            position: absolute; /* Posiciona o botão de logout */
            top: 20px; /* Distância do topo */
            right: 20px; /* Distância da direita */
            color: white; /* Cor do texto */
            background-color: red; /* Cor de fundo */
            border: none; /* Remove bordas */
            border-radius: 4px; /* Borda arredondada */
            padding: 10px; /* Espaçamento interno */
            cursor: pointer; /* Muda o cursor ao passar o mouse */
            font-size: 16px; /* Tamanho da fonte */
            transition: background-color 0.3s; /* Transição suave para a cor de fundo */
            z-index: 1; /* Garante que o botão fique acima do fade */
        }
        .btn-logout:hover {
            background-color: darkred; /* Cor ao passar o mouse */
        }
        .card-container {
            display: flex; /* Alinha todos os cards em linha */
            justify-content: center; /* Centraliza os cards na tela */
            flex-wrap: wrap; /* Permite quebra de linha se necessário */
            max-width: 900px; /* Limita a largura total dos cards */
            margin-top: 20px; /* Espaçamento entre o título e os cards */
            max-height: calc(100vh - 120px); /* Limita a altura da área de cards para evitar rolagem */
            overflow: auto; /* Permite rolagem apenas nos cards, se necessário */
            align-items: flex-start; /* Alinha os cards ao topo da container */
            margin-bottom: 0; /* Remove margem inferior para os cards */
            position: relative; /* Para permitir z-index */
            z-index: 1; /* Garante que os cards fiquem acima do fade */
        }
        .card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin: 15px; /* Aumenta o espaçamento entre os cards */
            padding: 30px; /* Aumenta o padding interno do card */
            text-align: center;
            width: 180px; /* Aumenta a largura de cada card */
            height: 160px; /* Aumenta a altura uniforme para todos os cards */
            transition: transform 0.3s;
            text-decoration: none; /* Remove o sublinhado do link */
            color: #333; /* Cor do texto */
            display: flex; /* Para alinhar ícone e texto verticalmente */
            flex-direction: column; /* Coloca ícone acima do texto */
            justify-content: center; /* Centraliza verticalmente */
        }
        .card:hover {
            transform: translateY(-5px); /* Efeito de elevação ao passar o mouse */
            background-color: #f9f9f9; /* Cor ao passar o mouse nos cards */
        }
        .card i {
            font-size: 40px; /* Tamanho do ícone aumentado */
            margin-bottom: 10px; /* Espaço entre o ícone e o texto */
            color: #003366; /* Azul escuro para o ícone */
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
<body>
    <div class="fade"></div> <!-- Fade div adicionada -->
    <h1>Bem-vindo, <?php echo $_SESSION['usuario']; ?>!</h1>
    <button class="btn-logout" onclick="location.href='logout.php'"><i class="fas fa-sign-out-alt"></i> Sair</button>
    <div class="card-container">
        <a href="turbinas.php" class="card">
            <i class="fas fa-cog"></i>
            <p>Cadastrar Turbinas</p>
        </a>
        <a href="manutencao.php" class="card">
            <i class="fas fa-wrench"></i>
            <p>Manutenção</p>
        </a>
        <a href="monitoramento.php" class="card">
            <i class="fas fa-chart-line"></i>
            <p>Monitoramento</p>
        </a>
        <a href="relatorios.php" class="card">
            <i class="fas fa-file-alt"></i>
            <p>Relatórios</p>
        </a>
        <a href="alertas.php" class="card">
            <i class="fas fa-exclamation-triangle"></i>
            <p>Ver Alertas</p>
        </a>
    </div>
</body>
</html>