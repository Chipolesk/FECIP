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

// Estabelecer a conexão com o SQL Server
$conn = sqlsrv_connect($serverName, $connectionOptions);

if ($conn === false) {
    die(json_encode(array('status' => 'erro', 'message' => 'Falha na conexão com o SQL Server: ' . print_r(sqlsrv_errors(), true))));
}

$nome_user = $_POST['nome_user'];
$novo_record = floatval($_POST['novo_record']);  // Converte o valor para float
$coluna_fase = $_POST['coluna_fase'];  // O nome da coluna correspondente à fase

// Atualizar o tempo recorde do usuário
$sql = "UPDATE jogos.HardvarInHelheim SET $coluna_fase = ? WHERE nome_user = ?;";
$params = array($novo_record, $nome_user);
$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

echo json_encode(array('status' => 'sucesso', 'message' => 'Recorde atualizado com sucesso.'));

// Fechar a conexão
sqlsrv_close($conn);
?>
