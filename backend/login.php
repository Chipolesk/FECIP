<?php

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
    
echo "VOCÊ ESTÁ CONECTADO AO SQL SERVER. . .";
    
    // Verificar se a conexão foi bem-sucedida
    if ($conn === false) {
        throw new Exception('Falha na conexão com o SQL Server: ' . print_r(sqlsrv_errors(), true));
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        error_log("Requisição POST recebida");
        
       if (isset($_POST['nome_user_login']) && isset($_POST['senha_user_login'])) {

    $nome_user_login = $_POST['nome_user_login'];  
    $senha_user_login = $_POST['senha_user_login'];  

    // Montar o comando SQL de inserção
    $sql = "SELECT COUNT(*) as total FROM digitalcore.usuario WHERE nome_user = ? AND senha_user = ?";

    // Preparar os parâmetros
    $params = array($nome_user_login, $senha_user_login);

    // Executar a query de inserção
    $stmt = sqlsrv_query($conn, $sql, $params);

    // Verificar se a query foi bem-sucedida
    if ($stmt === false) {
        echo "Erro ao executar a query.";
        die(print_r(sqlsrv_errors(), true)); // Exibe o erro detalhado
    } else {
        // Obter o resultado da contagem
        $resultado = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        $count = $resultado['total'];

        if ($count > 0) {
            echo "BEM-VINDO " . htmlspecialchars($nome_user);
        } else {
            echo "USUÁRIO NÃO ENCONTRADO";
        }
    }
} else {
    throw new Exception('Dados do usuário não recebidos corretamente.');
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
