<?php
namespace App\Controllers;

use App\Core\Response;

class IndexController
{
    public function index()
    {
        return Response::make()
            ->status(Response::HTTP_OK)
            ->view('index', ['name' => 'Amir'])
            ->send();
    }

    public function api()
    {
        return Response::make()
            ->json(['message' => 'Hello API'])
            ->status(200)
            ->header('X-App-Version', '1.0')
            ->send();
    }

    public function redirectToLogin()
    {
        return Response::make()->redirect('/login');
    }
}
