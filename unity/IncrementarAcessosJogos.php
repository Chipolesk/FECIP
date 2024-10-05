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

// Receber o nome do jogo via POST
$nome_jogo = $_POST['nome_jogo'];

// Incrementar o número de acessos do jogo
$sql = "UPDATE jogos SET acessos = acessos + 1 WHERE nome_jogo = ?";
$params = array($nome_jogo);
$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt === false) {
    die(json_encode(array('status' => 'erro', 'message' => 'Erro na atualização: ' . print_r(sqlsrv_errors(), true))));
}

echo json_encode(array('status' => 'sucesso', 'message' => 'Número de acessos atualizado com sucesso.'));

// Fechar a conexão
sqlsrv_close($conn);
?>
