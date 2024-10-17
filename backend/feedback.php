<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit;
}

header('Content-Type: application/json'); // Define como JSON para respostas corretas

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
        throw new Exception('Falha na conexão com o SQL Server: ' . json_encode(sqlsrv_errors(), JSON_PRETTY_PRINT));
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['feedback'])) {
            $feedback = $_POST['feedback'];
            error_log("Dados POST recebidos: " . print_r($feedback, true));

            // Prepara a query para inserção
            $sql = "INSERT INTO digitalcore.feedback (feedback) VALUES (?)";
            $params = array($feedback);

            $stmt = sqlsrv_query($conn, $sql, $params);

            if ($stmt === false) {
                throw new Exception('Falha ao inserir feedback: ' . json_encode(sqlsrv_errors(), JSON_PRETTY_PRINT));
            } else {
                // Retorna resposta de sucesso
                $response = array('status' => 'sucesso', 'message' => 'Feedback inserido com sucesso');
                echo json_encode($response);

                // Gera um alerta no navegador do cliente
                echo '<script>alert("OBRIGADO POR DEIXAR SEU COMENTÁRIO!");</script>';
                exit; // Encerra a execução após enviar a resposta
            }
        } else {
            throw new Exception('Dados de feedback não foram enviados corretamente.');
        }
    }
} catch (Exception $e) {
    // Retorna resposta de erro como JSON
    $response = array('status' => 'erro', 'message' => $e->getMessage());
    echo json_encode($response);
    exit;
}

// Fecha a conexão, se aberta
if (isset($conn) && $conn !== false) {
    sqlsrv_close($conn);
}
?>
