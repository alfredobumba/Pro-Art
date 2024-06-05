<?php
// Verifica se os dados do formulário foram enviados
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtém os dados do formulário
    $data = json_encode($_POST);
    
    // Caminho para o arquivo de log
    $file = 'logs/log_' . date('Y-m-d') . '.txt';

    // Adiciona os dados ao arquivo de log
    file_put_contents($file, $data . PHP_EOL, FILE_APPEND | LOCK_EX);

    // Responde ao cliente
    echo "Dados registrados com sucesso!";
} else {
    // Responde ao cliente se a requisição não for POST
    http_response_code(405);
    echo "Método não permitido. Esta página só aceita requisições POST.";
}
?>
