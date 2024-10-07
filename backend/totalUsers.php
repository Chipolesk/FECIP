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

    // Query para obter o nome do jogo e os acessos
    $sql = "SELECT nome_jogo, acessos_jogo FROM jogos";

    $stmt = sqlsrv_query($conn, $sql);

    if ($stmt === false) {
        throw new Exception('Falha ao consultar dados: ' . json_encode(sqlsrv_errors()));
    }

    $resultados = [];

    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        // Adiciona cada jogo ao array de resultados
        $resultados[] = [
            'nome_jogo' => $row['nome_jogo'],
            'acessos_jogo' => $row['acessos_jogo']
        ];
    }

    // Retorna os resultados em formato JSON
    echo json_encode($resultados);
} catch (Exception $e) {
    $response = array('status' => 'erro', 'message' => $e->getMessage());
    echo json_encode($response);
} finally {
    if (isset($conn) && $conn !== false) {
        sqlsrv_close($conn);
    }
}
?>
