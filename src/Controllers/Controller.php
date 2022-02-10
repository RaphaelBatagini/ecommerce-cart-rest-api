<?php

namespace Root\HashBackendChallenge\Controllers;

class Controller
{
    public function __construct($router)
    {
        $this->router = $router;
    }

    protected function returnJson(array $response)
    {
        echo json_encode($response);
        die;
    }
}