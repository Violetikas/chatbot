<?php
/**
 * Created by PhpStorm.
 * User: violetatamasauskiene
 * Date: 2019-05-18
 * Time: 14:07
 */

include (__DIR__ . '/vendor/autoload.php');
$configProvider = new \Service\ConfigProvider(__DIR__.'/config.json');

//var_dump($configProvider->getParameter('appId'));

$client = new \Service\QuestionProvider(new \GuzzleHttp\Client());

var_dump($client->getQuestion());
