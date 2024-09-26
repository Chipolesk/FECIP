<?php 

try{
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
  
      } catch (Exception $e) {
    // Capturar erros e retornar em formato JSON
echo "Caiu no catch: " . $e->getMessage();    

    
    $response = array('status' => 'erro', 'message' => $e->getMessage());
    echo json_encode($response);
} finally {
    // Fechar a conexão, se estiver aberta b
    if (isset($conn) && $conn !== false) {
        sqlsrv_close($conn);
    }
}

?>
