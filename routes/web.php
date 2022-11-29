<?php

\MyApplication\core\MyApplicationRoute::get('/')->action('MainController@index');
\MyApplication\core\MyApplicationRoute::post('/generate')->action('MainController@generate');