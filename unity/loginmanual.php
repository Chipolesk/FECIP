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

$username = $_POST['username'];
#$password = $_POST['password'];
$password = '123';
// Verificar se o usuário existe
$sql_user = "SELECT * FROM digitalcore.usuario WHERE nome_user = ?;";
$params_user = array($username);
$stmt_user = sqlsrv_query($conn, $sql_user, $params_user);

if ($stmt_user === false) {
    die(print_r(sqlsrv_errors(), true));
}

$row_user = sqlsrv_fetch_array($stmt_user, SQLSRV_FETCH_ASSOC);
if (!$row_user) {
    echo json_encode(array('status' => 'erro_usuario', 'message' => 'Usuário não encontrado.'));
} else {
    // Verificar se a senha está correta
    $sql_password = "SELECT * FROM digitalcore.usuario WHERE nome_user = ? AND senha_user = ?;";
    $params_password = array($username, $password);
    $stmt_password = sqlsrv_query($conn, $sql_password, $params_password);

    if ($stmt_password === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    $row_password = sqlsrv_fetch_array($stmt_password, SQLSRV_FETCH_ASSOC);
    if ($row_password) {
        echo json_encode(array('status' => 'sucesso', 'message' => 'Login bem-sucedido.'));
        // Criar uma sessão para o usuário
        session_start();
        $_SESSION['user'] = $row_user['nome_user'];
    } else {
        echo json_encode(array('status' => 'erro_senha', 'message' => 'Senha incorreta.'));
    }
}


// Fechar a conexão
sqlsrv_close($conn);
?>
