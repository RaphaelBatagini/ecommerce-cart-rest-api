<?php

namespace Root\HashBackendChallenge\Controllers;

use Root\HashBackendChallenge\Controllers\Controller;

class IndexController extends Controller
{
    public function index()
    {
        echo '<h1>Hash Backend Challenge</h1>';
        echo '<p>API desenvolvida como teste para vaga de dev backend.</p>';
    }

    public function error($params)
    {
        echo '<h1>Ops! Parece que algo deu errado.</h1>';
        if (!empty($params['errcode'])) {
            echo "<p>Error Code: {$params['errcode']}</p>";
        }
    }
}
