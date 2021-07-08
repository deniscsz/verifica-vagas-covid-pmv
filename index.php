<?php
require 'vendor/autoload.php';

$serv = 1476; //faixa dos 30 a 34 anos.
if (isset($argv[1]) && $argv[1] && is_numeric($argv[1])) {
    $serv = $argv[1];
}

function getMessageUnid($vagas, $description) {
    return "{$vagas} vagas disponíveis em {$description}.\n\n";
}
function getCommandShell($message) {
    return "osascript -e 'tell app \"Finder\" to display dialog \"$message\"'";
}

$urlApi = 'https://agendamento.vitoria.es.gov.br/api/';
$urlEndpointVagas = 'servicos/'.$serv.'/unidades/vagas?_=' . time();

// Create a client with a base URI
$client = new GuzzleHttp\Client(['base_uri' => $urlApi]);
$response = $client->request('GET', $urlEndpointVagas,[
    'headers' => ['Cache-Control' => 'no-cache'],
]);

if ($response->getStatusCode() == 200) {
    $body = $response->getBody();
    $data = \json_decode((string) $body, true);

    if (empty($data)) {
        echo "Não foram encontradas unidades. Provavelmente algo de errado está acontecendo. Verifique o serviço consultado" . "\n";
        exit(0);
    }

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