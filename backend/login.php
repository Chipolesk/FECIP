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

    // Verificar se a conexão foi bem-sucedida
    if ($conn === false) {
        throw new Exception('Falha na conexão com o SQL Server: ' . print_r(sqlsrv_errors(), true));
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        error_log("Requisição POST recebida");
        
        if (isset($_POST['nome_user_login']) && isset($_POST['senha_user_login'])) {
            $nome_user_login = $_POST['nome_user_login'];  
            $senha_user_login = $_POST['senha_user_login'];  

            // Montar o comando SQL
            $sql = "SELECT * FROM digitalcore.usuario WHERE nome_user = ? AND senha_user = ?";
            $params = array($nome_user_login, $senha_user_login);

            // Executar a query
            $stmt = sqlsrv_query($conn, $sql, $params);

            // Verificar se a query foi bem-sucedida
            if ($stmt === false) {
                $error = sqlsrv_errors();
                throw new Exception('Erro ao executar a query: ' . json_encode($error));
            }

            // Obter o resultado
            $usuario = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

            if ($usuario) {
                // Se o usuário foi encontrado, preparar resposta de sucesso
                $response = array('status' => 'sucesso', 'message' => 'BEM-VINDO ' . htmlspecialchars($usuario['nome_user']));
                
                // Gravar os dados do usuário em um arquivo JSON
                $dados_usuario = array(
                    "nome_user" => $usuario['nome_user'],
                    "icone_user" => $usuario['icone_user']
                );
                
                $arquivo_json = json_encode($dados_usuario);
                file_put_contents('ultimo_usuario.json', $arquivo_json);

            } else {
                // Se o usuário não foi encontrado, preparar resposta de erro
                $response = array('status' => 'erro', 'message' => 'USUÁRIO NÃO ENCONTRADO');
            }

            echo json_encode($response);
        } else {
            throw new Exception('Dados do usuário não recebidos corretamente.');
        }
    }
} catch (Exception $e) {
    $response = array('status' => 'erro', 'message' => $e->getMessage());
    echo json_encode($response);
} finally {
    // Fechar a conexão, se estiver aberta
    if (isset($conn) && $conn !== false) {
        sqlsrv_close($conn);
    }
}
?>
