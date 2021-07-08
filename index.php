<?php
require 'vendor/autoload.php';

function getMessageUnid($vagas, $description) {
    return "{$vagas} vagas disponíveis em {$description}.\n\n";
}
function getCommandShell($message) {
    return "osascript -e 'tell app \"Finder\" to display dialog \"$message\"'";
}

$urlApi = 'https://agendamento.vitoria.es.gov.br/api/';
$urlEndpointVagas = 'servicos/1476/unidades/vagas?_=' . time();

// Create a client with a base URI
$client = new GuzzleHttp\Client(['base_uri' => $urlApi]);
$response = $client->request('GET', $urlEndpointVagas,[
    'headers' => ['Cache-Control' => 'no-cache'],
]);

if ($response->getStatusCode() == 200) {
    $body = $response->getBody();
    $data = \json_decode((string) $body, true);

    echo "Data: " . date("Y-m-d H:i:s") . "\n";

    $unidSelected = [];
    foreach ($data as $unid) {
        if (isset($unid['vagasdisponiveis']) && $unid['vagasdisponiveis'] > 0) {
            $unidSelected[] = $unid;
        }
    }

    if (!empty($unidSelected)) {
        $message = '';
        foreach ($unidSelected as $unid) {
            $message .= getMessageUnid($unid['vagasdisponiveis'], $unid['name'] . ' '. $unid['descricao']);
        }

        if ($message) {
            echo "Encontrou vagas! {$message}\n";
            exec(getCommandShell($message));
        }
    } else {
        echo "Não há vagas!\n";
    }
} else {
    echo "Não foi possível conectar na URL da API.\n";
}