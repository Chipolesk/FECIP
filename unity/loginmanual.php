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
$password = $_POST['password'];

// Verificar se o usuário existe e a senha está correta
$sql_user = "SELECT * FROM digitalcore.usuario WHERE nome_user = ? AND senha_user = ?;";
$params_user = array($username, $password);
$stmt_user = sqlsrv_query($conn, $sql_user, $params_user);

if ($stmt_user === false) {
    die(print_r(sqlsrv_errors(), true));
}

$row_user = sqlsrv_fetch_array($stmt_user, SQLSRV_FETCH_ASSOC);
if (!$row_user) {
    echo json_encode(array('status' => 'erro_usuario', 'message' => 'Usuário não encontrado ou senha incorreta.'));
} else {
    // Verificar se o usuário já existe na tabela jogos.digismash
    $sql_digismash = "SELECT COUNT(*) as count FROM jogos.digismash WHERE nome_user = ?";
    $params_digismash = array($username);
    $stmt_digismash = sqlsrv_query($conn, $sql_digismash, $params_digismash);

    if ($stmt_digismash === false) {
        die(json_encode(array('status' => 'erro', 'message' => 'Erro na consulta: ' . print_r(sqlsrv_errors(), true))));
    }

    $row_digismash = sqlsrv_fetch_array($stmt_digismash, SQLSRV_FETCH_ASSOC);
    if ($row_digismash['count'] > 0) {
        echo json_encode(array('status' => 'sucesso', 'message' => 'Login bem-sucedido. Usuário já existe na tabela jogos.digismash.'));
    } else {
        // Inserir o usuário na tabela jogos.digismash se não existir
        $sql_insert = "INSERT INTO jogos.digismash (nome_user, minutos_jogados) VALUES (?, 0)";
        $params_insert = array($username);
        $stmt_insert = sqlsrv_query($conn, $sql_insert, $params_insert);

        if ($stmt_insert === false) {
            die(json_encode(array('status' => 'erro', 'message' => 'Erro ao inserir usuário: ' . print_r(sqlsrv_errors(), true))));
        }

        echo json_encode(array('status' => 'sucesso', 'message' => 'Login bem-sucedido. Usuário inserido na tabela jogos.digismash.'));
    }
}

// Fechar a conexão
sqlsrv_close($conn);
?>
