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

// Receber o username e a senha via POST
$username = $_POST['username'];
$password = $_POST['password'];

// Verificar se o usuário existe
$sql = "SELECT senha FROM jogos.digismash WHERE nome_user = ?";
$params = array($username);
$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt === false) {
    die(json_encode(array('status' => 'erro', 'message' => 'Erro na consulta: ' . print_r(sqlsrv_errors(), true))));
}

$row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

if ($row) {
    // Verificar se a senha está correta
    if (password_verify($password, $row['senha_user'])) {
        echo json_encode(array('status' => 'sucesso', 'message' => 'Login bem-sucedido.'));
    } else {
        echo json_encode(array('status' => 'erro', 'message' => 'Senha incorreta.'));
    }
} else {
    echo json_encode(array('status' => 'erro', 'message' => 'Usuário não encontrado.'));
}

// Fechar a conexão
sqlsrv_close($conn);
?>
