<?php
header("Access-Control-Allow-Origin: *"); 
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit;
}

header('Content-Type: application/json');

try {
    // Informações de conexão
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
        throw new Exception('Falha na conexão com o SQL Server: ' . json_encode(sqlsrv_errors()));
    }
    echo "VOCÊ ESTÁ CONECTADO AO SQL SERVER. . .";

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['nome_user']) && isset($_POST['senha_user']) && isset($_POST['icone_escolhido'])) {
            $nome_user = $_POST['nome_user'];  
            $senha_user = $_POST['senha_user'];  
            $icone_user = basename($_POST['icone_escolhido']);
            
            error_log("Dados POST recebidos: " . print_r($_POST, true));


            $sql = "INSERT INTO digitalcore.usuario (nome_user, senha_user, icone_user) VALUES (?, ?, ?)";
            $params = array($nome_user, $senha_user, $icone_user);

            $stmt = sqlsrv_query($conn, $sql, $params);

            if ($stmt === false) {
                throw new Exception('Falha ao inserir usuário: ' . json_encode(sqlsrv_errors()));
            } else {
                $response = array('status' => 'sucesso', 'message' => 'Usuário inserido com sucesso!');
                echo json_encode($response);
            }
        } else {
            throw new Exception('Dados do usuário não foram enviados corretamente.' . json_encode(sqlsrv_errors()));
        }
    }
} catch (Exception $e) {
    $response = array('status' => 'erro', 'message' => $e->getMessage());
    echo json_encode($response);
} finally {
    if (isset($conn) && $conn !== false) {
        sqlsrv_close($conn);
    }
}
?>
