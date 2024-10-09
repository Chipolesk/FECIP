<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit;
}

header('Content-Type: application/json'); // Define como JSON

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

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Recebe os dados enviados pelo corpo da requisição
        $data = json_decode(file_get_contents('php://input'), true);

        if (isset($data['feedback'])) {
            $feedback = $data['feedback'];  
            
            error_log("Dados POST recebidos: " . print_r($data, true));

            // Prepara a query para inserção
            $sql = "INSERT INTO digitalcore.feedback (feedback) VALUES (?)";
            $params = array($feedback); // Passa o feedback como array

            $stmt = sqlsrv_query($conn, $sql, $params);

            if ($stmt === false) {
                throw new Exception('Falha ao inserir feedback: ' . json_encode(sqlsrv_errors()));
            } else {
                // Retorna resposta de sucesso
                $response = array('status' => 'sucesso', 'message' => 'Feedback inserido com sucesso');
            }
        } else {
            throw new Exception('Dados de feedback não foram enviados corretamente.');
        }
    }
} catch (Exception $e) {
    // Retorna resposta de erro
    $response = array('status' => 'erro', 'message' => $e->getMessage());
}

// Retorna a resposta em JSON
echo json_encode($response);

if (isset($conn) && $conn !== false) {
    sqlsrv_close($conn);
}
?>
