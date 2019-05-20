<?php
/**
 * Created by PhpStorm.
 * User: violetatamasauskiene
 * Date: 2019-05-18
 * Time: 13:33
 */

namespace Service;

use GuzzleHttp\Client;


class QuestionProvider
{
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function getQuestion()
    {
        $response = $this->client->get(
            'https://opentdb.com/api.php?amount=1&category=11&difficulty=easy&type=boolean'
        );

        $var = json_decode($response->getBody()->getContents(), true);
        $klausimas = $var["results"][0]["question"];

        return $klausimas;

    }
}

