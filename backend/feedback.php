<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Define o tipo de resposta como HTML
header('Content-Type: text/html; charset=UTF-8');

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

    // Conecta ao banco de dados
    $conn = sqlsrv_connect($serverName, $connectionOptions);
    if ($conn === false) {
        throw new Exception('Falha na conexão com o SQL Server: ' . json_encode(sqlsrv_errors()));
    }

    // Verifica se a requisição é POST e se o feedback foi enviado
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['feedback'])) {
        $feedback = $_POST['feedback'];

        // Prepara a query de inserção
        $sql = "INSERT INTO digitalcore.feedback (feedback) VALUES (?)";
        $params = array($feedback); 

        $stmt = sqlsrv_query($conn, $sql, $params);
        if ($stmt === false) {
            throw new Exception('Falha ao inserir feedback: ' . json_encode(sqlsrv_errors()));
        } else {
            // Exibe uma mensagem de sucesso e redireciona para o index.html
            echo "
                <script>
                    alert('Obrigado por deixar seu comentário!');
                    window.location.href = '../index.html'; // Redireciona para a página inicial
                </script>
            ";
        }
    } else {
        throw new Exception('Feedback não enviado corretamente.');
    }
} catch (Exception $e) {
    // Exibe uma mensagem de erro e redireciona para o index.html
    echo "
        <script>
            alert('Erro: " . htmlspecialchars($e->getMessage()) . "');
            window.location.href = '../index.html'; // Redireciona para a página inicial
        </script>
    ";
}

// Fecha a conexão
if (isset($conn) && $conn !== false) {
    sqlsrv_close($conn);
}
?>
