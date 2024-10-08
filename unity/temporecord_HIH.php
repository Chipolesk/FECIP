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
$tabela_fase = $_POST['tabela_fase'];  // O nome da coluna correspondente à fase (temporecord_fase2 ou temporecord_fase3)

// Buscar o tempo recorde do usuário
$sql = "SELECT $tabela_fase FROM jogos.HardvarInHelheim WHERE nome_user = ?";
$params = array($nome_usuario);
$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

$row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
if ($row) {
    echo $row[$tabela_fase];
} else {
    echo json_encode(array('status' => 'erro_usuario', 'message' => 'Usuário não encontrado.'));
}

// Fechar a conexão
sqlsrv_close($conn);
?>
