<?php
header("Access-Control-Allow-Origin: *"); 
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit;
}

header('Content-Type: text/html');

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
                
                 // Se o usuário foi encontrado, preparar resposta de sucesso
                $response = array('status' => 'sucesso', 'message' => 'Usuario Inserido com Sucesso ' . htmlspecialchars($nome_user));
                
                // Gravar os dados do usuário em um arquivo JSON
                $dados_usuario = array(
                    "nome_user" => htmlspecialchars($nome_user),
                    "icone_user" => htmlspecialchars($icone_user)
                );

                $arquivo_json = json_encode($dados_usuario);
                file_put_contents('ultimo_usuario.json', $arquivo_json);
            }
             // Obter status e mensagem da resposta
            $status = $response['status'];
            $message = $response['message'];

            // Gerar HTML
            $html = '';
$html .= '<title>Confirmação</title>';
$html .= '<style>';
$html .= 'body { font-family: Arial, sans-serif; background-color: #f4f4f9; color: #333; text-align: center; padding: 50px;}';
$html .= '.container {background-color: #fff; padding: 20px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); display: inline-block;}';
$html .= 'p { font-size: 18px; margin: 10px 0;}';
$html .= '.btn {background-color: #007bff; color: #fff; padding: 10px 20px; border: none; border-radius: 5px; text-decoration: none; font-size: 16px;}';
$html .= '.btn:hover {background-color: #0056b3;}';
$html .= '</style>';
$html .= '<script>';
$html .= 'var count = 5;';
$html .= 'var interval = setInterval(function() {';
$html .= '    document.getElementById("countdown").innerHTML = count + "s";';
$html .= '    count--;';
$html .= '    if (count < 0) {';
$html .= '        clearInterval(interval);';
$html .= '        window.location.href = "../index.html";';
$html .= '    }';
$html .= '}, 1000);';
$html .= '</script>';
$html .= '<body>';
$html .= '<div class="container">';
$html .= '<h1>' . ($status == "sucesso" ? "Sucesso!" : "Erro!") . '</h1>';
$html .= '<p>' . htmlspecialchars($message) . '</p>';
$html .= '<h3>Voltaremos à página principal em <span id="countdown">5s</span></h3>';
$html .= '</div>';
$html .= '</body>';
$html .= '</html>';

echo $html;
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
