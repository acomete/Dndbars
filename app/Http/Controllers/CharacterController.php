<?php

namespace App\Http\Controllers;

use App\Services\CharacterService;

class CharacterController extends Controller
{
    private $characterIds = [
        30155090, //Helium
        32836801, // Fer
        29872982, // Finnan
        29975327, // Gorlgröm
        36813674, // Hope Laslikar
        30159477, // Nur Khan
    ];

    public function index(CharacterService $service)
    {
        $service->auth();

        $characters = array_map(function (int $id) use ($service) {
            return (object) $service->getCharacterData($id);
        }, $this->characterIds);

        return view('characters', ['characters' => $characters]);
    }
}
