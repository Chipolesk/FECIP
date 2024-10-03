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

// Receber o username via POST
$username = $_POST['username'];

// Atualizar o tempo jogado do usuário
$sql = "UPDATE jogos.digismash SET minutos_jogados = minutos_jogados + 2 WHERE nome_user = ?";
$params = array($username);
$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt === false) {
    die(json_encode(array('status' => 'erro', 'message' => 'Erro na atualização: ' . print_r(sqlsrv_errors(), true))));
}

echo json_encode(array('status' => 'sucesso', 'message' => 'Tempo jogado atualizado com sucesso.'));

// Fechar a conexão
sqlsrv_close($conn);
?>
