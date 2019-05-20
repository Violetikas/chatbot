<?php

use GuzzleHttp\Client;
use Service\ConfigProvider;
use Service\QuestionProvider;

include(__DIR__ . '/vendor/autoload.php');
$configProvider = new ConfigProvider(__DIR__ . '/config.json');
$questionProvider = new QuestionProvider(new Client());

if (isset($_REQUEST['hub_challenge'])) {
    $challenge = $_REQUEST['hub_challenge'];
    if ($_REQUEST['hub_verify_token'] === $configProvider->getParameter('verify_token')) {
        echo $challenge;
        die();
    }
}

$input = json_decode(file_get_contents('php://input'), true);

if ($input === null) {
    exit;
}

$message = $input['entry'][0]['messaging'][0]['message']['text'];
$conversationId = $input['entry'][0]['messaging'][0]['sender']['id'];

$fb = new \Facebook\Facebook([
    'app_id' => $configProvider->getParameter('appId'),
    'app_secret' => $configProvider->getParameter('appSecret'),
]);

$data = [
    'messaging_type' => 'RESPONSE',
    'recipient' => [
        'id' => $conversationId,
    ],
    'message' => [
        'text' => 'You wrote: ' . $message,
    ]
];

// Construct messages file name.
$sessionDir = __DIR__ . DIRECTORY_SEPARATOR . 'var' . DIRECTORY_SEPARATOR . 'tmp' . DIRECTORY_SEPARATOR . 'session';
$sessionFile = $sessionDir . DIRECTORY_SEPARATOR . $conversationId . '.json'; // assume $conversationId is a valid filename

// Attempt to read messages.
if (is_file($sessionFile)) {
    $messages = json_decode(file_get_contents($sessionFile));
    if (!$messages) {
        // TODO: log error
        $messages = [];
    }
}

// TODO: respond using stored (or not) messages.


// Store messages.
$messages[] = $message;
file_put_contents($sessionFile, json_encode($messages));

if ($message === 'start') {
    $data['message']['text'] = $questionProvider->getQuestion();
}

$response = $fb->post('/me/messages', $data, $configProvider->getParameter('access_token'));
