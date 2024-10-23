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

$colunas = $_POST['colunas'];
$tabela = $_POST['tabela'];
$ordenarPor = isset($_POST['ordenarpor']) ? $_POST['ordenarpor'] : null;
$ordem = isset($_POST['ordem']) ? $_POST['ordem'] : null;

// Buscar dados na tabela especificada
if ($ordenarPor !== null && $ordem !== null) {
    $sql = "SELECT $colunas FROM $tabela WHERE $ordenarPor IS NOT NULL ORDER BY $ordenarPor $ordem;";
} else {
    $sql = "SELECT $colunas FROM $tabela;";
}

$stmt = sqlsrv_query($conn, $sql);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

$resultados = array();
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $resultados[] = implode('<*>', $row);
}

echo implode('<*>', $resultados);

// Fechar a conexão
sqlsrv_close($conn);
?>
