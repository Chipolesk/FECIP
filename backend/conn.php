<?php
header("Access-Control-Allow-Origin: *"); // Permite acesso de qualquer origem
header("Access-Control-Allow-Methods: POST, GET, OPTIONS"); // Métodos permitidos
header("Access-Control-Allow-Headers: Content-Type"); // Cabeçalhos permitidos

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    // Se for uma requisição OPTIONS, retornar 200 e sair
    http_response_code(200);
    exit;
}

header('Content-Type: application/json');
$data = json_decode(file_get_contents('php://input'), true);

try {
    // Defina as informações de conexão
    $serverName = "digitalcoreserver.database.windows.net";  // Host do servidor SQL
    $connectionOptions = array(
        "Database" => "DigitalCoreDB",  // Nome do banco de dados
        "Uid" => "DIGITAL.CORE",  // Nome de usuário
        "PWD" => "@FECIP2K24",  // Senha
        "Encrypt" => true,  // SSL habilitado (recomendado para Azure)
        "TrustServerCertificate" => false,  // Certificado SSL
        "LoginTimeout" => 30,  // Timeout da conexão
    );

    
    // Estabelecer a conexão com o SQL Server
    $conn = sqlsrv_connect($serverName, $connectionOptions);
    
echo json_encode(array('message' => 'VOCÊ ESTÁ CONECTADO AO SQL SERVER. . .'));
    
    // Verificar se a conexão foi bem-sucedida
    if ($conn === false) {
        throw new Exception('Falha na conexão com o SQL Server: ' . print_r(sqlsrv_errors(), true));
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        error_log("Requisição POST recebida");
        
        if (isset($_POST['nome_user']) && isset($_POST['senha_user']) && isset($_POST['icone_escolhido'])) {

            $nome_user = $_POST['nome_user'];  
            $senha_user = $_POST['senha_user'];  
            $icone_user = $_POST['icone_escolhido'];

            // Montar o comando SQL de inserção
            $sql = "INSERT INTO digitalcore.usuario (nome_user, senha_user, icone_user) VALUES (?, ?, ?)";

            // Preparar os parâmetros
            $params = array($nome_user, $senha_user, $icone_user);

            // Executar a query de inserção
            $stmt = sqlsrv_query($conn, $sql, $params);

            // Verificar se a query foi bem-sucedida
            if ($stmt === false) {
                throw new Exception('Falha ao inserir usuário: ' . print_r(sqlsrv_errors(), true));
            } else {
                $response = array('status' => 'sucesso', 'message' => 'Usuário inserido com sucesso!');
                
                echo json_encode($response);
            }
        } else {
            throw new Exception('Dados do usuário não foram enviados corretamente.');
        }
    }
} catch (Exception $e) {
    // Capturar erros e retornar em formato JSON
echo "Caiu no catch: " . $e->getMessage();    

    
    $response = array('status' => 'erro', 'message' => $e->getMessage());
    echo json_encode($response);
} finally {
    // Fechar a conexão, se estiver aberta
    if (isset($conn) && $conn !== false) {
        sqlsrv_close($conn);
    }
}
?>
