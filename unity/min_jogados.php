<?php
// Defina as informações de conexão
$serverName = "digitalcoreserver.database.windows.net";
$connectionOptions = array(
    "Database" => "DigitalCoreDB",
    "Uid" => "DIGITAL.CORE",
    "PWD" => "@FECIP2K24",
    "Encrypt" => true,
    "TrustServerCertificate" => false,
    "LoginTimeout" => 30,
);

$conn = sqlsrv_connect($serverName, $connectionOptions);

if ($conn === false) {
    die(json_encode(array('status' => 'erro', 'message' => 'Falha na conexão com o SQL Server: ' . print_r(sqlsrv_errors(), true))));
}

// Receber o nome do jogo e o nome do usuário via POST
$nome_jogo = $_POST['nome_jogo'];
$nome_user = $_POST['nome_user'];

// Construir a consulta SQL dinamicamente
$sql = "UPDATE $nome_jogo SET minutos_jogados = minutos_jogados + 1 WHERE nome_user = ?;";
$params = array($nome_user);
$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt === false) {
    die(json_encode(array('status' => 'erro', 'message' => 'Erro na atualização: ' . print_r(sqlsrv_errors(), true))));
}

echo json_encode(array('status' => 'sucesso', 'message' => 'Tempo jogado atualizado com sucesso.'));

// Fechar a conexão
sqlsrv_close($conn);
?>
