<?php
session_start();
include 'conexao.php';

// Verifique se o usuário está logado
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}

$id_usuario = $_SESSION['id_usuario'];

// Consulta para obter as turbinas associadas ao usuário logado
$query_turbinas = "SELECT * FROM turbinas WHERE id_usuario = ?";
$stmt_turbinas = $conexao->prepare($query_turbinas);
$stmt_turbinas->bind_param("i", $id_usuario);
$stmt_turbinas->execute();
$turbinas = $stmt_turbinas->get_result();
$stmt_turbinas->close();

// Consulta para obter manutenções com o nome da turbina
$query_manutencao = "
    SELECT manutencoes.id_manutencao, manutencoes.data_manutencao, manutencoes.descricao, manutencoes.custo, 
           turbinas.nome AS nome_turbina
    FROM manutencoes
    JOIN turbinas ON manutencoes.id_turbina = turbinas.id_turbina
    WHERE manutencoes.id_usuario = ?";
$stmt_manutencao = $conexao->prepare($query_manutencao);
$stmt_manutencao->bind_param("i", $id_usuario);
$stmt_manutencao->execute();
$manutencao = $stmt_manutencao->get_result();
$stmt_manutencao->close();

// Consulta para obter monitoramentos com o nome da turbina
$query_monitoramento = "
    SELECT monitoramento.id_monitoramento, monitoramento.data_hora, monitoramento.energia_gerada, monitoramento.status, 
           turbinas.nome AS nome_turbina
    FROM monitoramento
    JOIN turbinas ON monitoramento.id_turbina = turbinas.id_turbina
    WHERE monitoramento.id_usuario = ?";
$stmt_monitoramento = $conexao->prepare($query_monitoramento);
$stmt_monitoramento->bind_param("i", $id_usuario);
$stmt_monitoramento->execute();
$monitoramento = $stmt_monitoramento->get_result();
$stmt_monitoramento->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <link rel="stylesheet" href="css/estilos.css">
    <title>Relatórios</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #e9ecef;
            padding: 20px;
            margin: 0;
            color: #343a40;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #007bff;
        }

        h3 {
            color: #28a745;
            border-bottom: 2px solid #28a745;
            padding-bottom: 5px;
            margin-bottom: 15px;
            font-size: 1.5em;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            border-radius: 8px;
            overflow: hidden;
            background-color: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        th, td {
            border: 1px solid #dee2e6;
            padding: 12px;
            text-align: left;
            transition: background-color 0.3s ease;
        }

        th {
            background-color: #f8f9fa;
            color: #495057;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #e2e6ea;
        }

        .btn-voltar {
            display: block;
            margin: 20px auto;
            padding: 10px 20px;
            background-color: #007bff;
            border: none;
            border-radius: 4px;
            color: white;
            font-size: 16px;
            text-align: center;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn-voltar:hover {
            background-color: #0056b3;
        }

        @media (max-width: 768px) {
            table, th, td {
                display: block;
                width: 100%;
            }

            th {
                position: absolute;
                top: -9999px;
                left: -9999px;
            }

            td {
                text-align: right;
                position: relative;
                padding-left: 50%;
            }

            td:before {
                position: absolute;
                left: 0;
                width: 50%;
                padding-left: 10px;
                white-space: nowrap;
                font-weight: bold;
                text-align: left;
            }
        }
    </style>
</head>
<body>
    <h2>Relatórios</h2>

    <h3>Turbinas Cadastradas</h3>
    <table>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Localização</th>
        </tr>
        <?php while ($turbina = $turbinas->fetch_assoc()) { ?>
            <tr>
                <td><?= $turbina['id_turbina'] ?></td>
                <td><?= htmlspecialchars($turbina['nome']) ?></td>
                <td><?= htmlspecialchars($turbina['localizacao']) ?></td>
            </tr>
        <?php } ?>
    </table>

    <h3>Manutenções Registradas</h3>
    <table>
        <tr>
            <th>ID Manutenção</th>
            <th>Nome da Turbina</th>
            <th>Data</th>
            <th>Descrição</th>
            <th>Custo (R$)</th>
        </tr>
        <?php while ($manut = $manutencao->fetch_assoc()) { ?>
            <tr>
                <td><?= $manut['id_manutencao'] ?></td>
                <td><?= htmlspecialchars($manut['nome_turbina']) ?></td>
                <td><?= htmlspecialchars($manut['data_manutencao']) ?></td>
                <td><?= htmlspecialchars($manut['descricao']) ?></td>
                <td><?= htmlspecialchars($manut['custo']) ?></td>
            </tr>
        <?php } ?>
    </table>

    <h3>Monitoramento</h3>
    <table>
        <tr>
            <th>ID Monitoramento</th>
            <th>Nome da Turbina</th>
            <th>Data e Hora</th>
            <th>Energia Gerada (kWh)</th>
            <th>Status</th>
        </tr>
        <?php while ($monitor = $monitoramento->fetch_assoc()) { ?>
            <tr>
                <td><?= $monitor['id_monitoramento'] ?></td>
                <td><?= htmlspecialchars($monitor['nome_turbina']) ?></td>
                <td><?= htmlspecialchars($monitor['data_hora']) ?></td>
                <td><?= htmlspecialchars($monitor['energia_gerada']) ?></td>
                <td><?= htmlspecialchars($monitor['status']) ?></td>
            </tr>
        <?php } ?>
    </table>

    <form action="dashboard.php" method="GET">
        <button type="submit" class="btn-voltar">Voltar para Dashboard</button>
    </form>
</body>
</html>
