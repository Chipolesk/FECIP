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

// Receber o username via POST
$nome_user = $_POST['nome_user'];

// Verificar se o usuário já existe
$sql = "SELECT COUNT(*) as count FROM jogos.digismash WHERE nome_user = ?";
$params = array($nome_user);
$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt === false) {
    die(json_encode(array('status' => 'erro', 'message' => 'Erro na consulta: ' . print_r(sqlsrv_errors(), true))));
}

$row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

if ($row['count'] > 0) {
    echo json_encode(array('status' => 'sucesso', 'message' => 'Usuário já existe.'));
} else {
    // Inserir o usuário se não existir
    $sql = "INSERT INTO jogos.digismash (nome_user, minutos_jogados) VALUES (?, 0)";
    $params = array($nome_user);
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        die(json_encode(array('status' => 'erro', 'message' => 'Erro ao inserir usuário: ' . print_r(sqlsrv_errors(), true))));
    }

    echo json_encode(array('status' => 'sucesso', 'message' => 'Usuário inserido com sucesso.'));
}

// Fechar a conexão
sqlsrv_close($conn);
?>
