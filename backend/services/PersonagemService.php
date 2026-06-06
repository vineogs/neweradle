<?php

class PersonagemService
{
    public static function listar()
    {
        $file = __DIR__ . '/../data/personagens.json';

        if (!file_exists($file)) {
            return [];
        }

        $data = file_get_contents($file);

        if ($data === false) {
            return [];
        }

        $json = json_decode($data, true);

        if (!is_array($json)) {
            return [];
        }

        return $json;
    }
}
