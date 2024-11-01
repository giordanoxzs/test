<?php
$host = 'localhost';
$usuario = 'root';
$senha = '';
$banco = 'monitoramento_energias';

$conexao = new mysqli($host, $usuario, $senha, $banco);

if ($conexao->connect_error) {
    die('Erro de Conexão: ' . $conexao->connect_error);
}
?>
