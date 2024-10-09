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

    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        if (isset($_GET['nome_jogo'])) {
            $nome_jogo = $_GET['nome_jogo'];

            // Buscar as curtidas atuais
            $sqlSelect = "SELECT curtidas_jogo FROM jogos WHERE nome_jogo = ?";
            $paramsSelect = array($nome_jogo);
            $stmtSelect = sqlsrv_query($conn, $sqlSelect, $paramsSelect);
            
            if ($stmtSelect === false) {
                throw new Exception('Falha ao buscar o jogo: ' . json_encode(sqlsrv_errors()));
            }

            $curtidasAtuais = 0;
            if (sqlsrv_fetch($stmtSelect)) {
                $curtidasAtuais = sqlsrv_get_field($stmtSelect, 0);
            }

            // Enviar a resposta com o número de curtidas
            $response = array('curtidas_jogo' => $curtidasAtuais);
            echo json_encode($response);
        } else {
            throw new Exception('Nome do jogo não fornecido.');
        }
    } elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Recebe os dados enviados como JSON
        $data = json_decode(file_get_contents('php://input'), true);

        if (isset($data['nome_jogo']) && isset($data['curtidas_jogo'])) {
            $nome_jogo = $data['nome_jogo'];
            $curtidas_jogo = $data['curtidas_jogo'];

            // Atualiza o número de curtidas no banco
            $sqlUpdate = "UPDATE jogos SET curtidas_jogo = ? WHERE nome_jogo = ?";
            $paramsUpdate = array($curtidas_jogo, $nome_jogo);
            $stmtUpdate = sqlsrv_query($conn, $sqlUpdate, $paramsUpdate);

            if ($stmtUpdate === false) {
                throw new Exception('Falha ao atualizar o número de curtidas: ' . json_encode(sqlsrv_errors()));
            }

            // Enviar a resposta de sucesso com o número atualizado de curtidas
            $response = array('status' => 'sucesso', 'curtidas_jogo' => $curtidas_jogo);
            echo json_encode($response);
        } else {
            throw new Exception('Dados incompletos: nome do jogo ou curtidas não foram enviados.');
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
