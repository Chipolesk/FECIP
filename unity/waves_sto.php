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

$nome_user = $_POST['nome_user'];
$wave = intval($_POST['wave']);

// Buscar o wave registrado do usuário na tabela jogos.ShardsTakeOver
$sql = "SELECT wave_recorde FROM jogos.ShardsTakeOver WHERE nome_user = ?;";
$params = array($nome_user);
$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

$row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
if ($row) {
    $wave_registrado = intval($row['wave_recorde']);
    if ($wave > $wave_registrado) {
        // Atualizar o wave registrado se o novo wave for maior
        $sql_update = "UPDATE jogos.ShardsTakeOver SET wave_recorde = ? WHERE nome_user = ?;";
        $params_update = array($wave, $nome_user);
        $stmt_update = sqlsrv_query($conn, $sql_update, $params_update);

        if ($stmt_update === false) {
            die(json_encode(array('status' => 'erro', 'message' => 'Erro ao atualizar wave: ' . print_r(sqlsrv_errors(), true))));
        }

        echo json_encode(array('status' => 'sucesso', 'message' => 'Wave atualizado com sucesso.'));
    } else {
        echo json_encode(array('status' => 'menor', 'message' => 'O wave enviado é menor ou igual ao wave registrado.'));
    }
} else {
    echo json_encode(array('status' => 'erro_usuario', 'message' => 'Usuário não encontrado.'));
}

// Fechar a conexão
sqlsrv_close($conn);
?>
