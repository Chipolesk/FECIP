<?php
session_start();

// Limpar a sessão ou lógica do backend para logout
// Por exemplo, você pode remover o usuário logado do arquivo JSON
$jsonFile = 'ultimo_usuario.json';
$data = json_decode(file_get_contents($jsonFile), true);

// Atualizar o JSON para indicar que não há usuário logado
$data['nome_user'] = 'Nenhum usuário logado';

file_put_contents($jsonFile, json_encode($data, JSON_PRETTY_PRINT));

?>
