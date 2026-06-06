<?php

class GameService
{
    public static function personagemDoDia(array $personagens)
    {
        $data = date('Y-m-d');

        $hash = hash('sha256', $data . 'NEWERADLE_SECRET');

        $seed = hexdec(substr($hash, 0, 12));

        $index = $seed % count($personagens);

        return $personagens[$index];
    }

    public static function personagemAleatorio(array $personagens)
    {
        return $personagens[array_rand($personagens)];
    }

    public static function gerarDicas($tentativa, $secreto)
    {
        return [
            "nome" => self::compareSimple($tentativa['nome'], $secreto['nome']),

            "genero" => self::compareSimple(
                $tentativa['atributos']['genero'],
                $secreto['atributos']['genero']
            ),

            "estadoAtual" => self::compareSimple(
                $tentativa['atributos']['estadoAtual'],
                $secreto['atributos']['estadoAtual']
            ),

            "idade" => self::compareNumber(
                $tentativa['atributos']['idade'],
                $secreto['atributos']['idade']
            ),

            "elemento" => self::compareArray(
                $tentativa['atributos']['elemento'],
                $secreto['atributos']['elemento']
            ),

            "afiliacao" => self::compareArray(
                $tentativa['atributos']['afiliacao'],
                $secreto['atributos']['afiliacao']
            ),

            "primeiraAparicao" => self::compareSimple(
                $tentativa['atributos']['primeiraAparicao'],
                $secreto['atributos']['primeiraAparicao']
            ),

            "cabelo" => self::compareArray(
                $tentativa['atributos']['cabelo'],
                $secreto['atributos']['cabelo']
            ),

            "nacionalidade" => self::compareSimple(
                $tentativa['atributos']['nacionalidade'],
                $secreto['atributos']['nacionalidade']
            ),
        ];
    }

    private static function compareSimple($a, $b)
    {
        return $a === $b ? "correct" : "wrong";
    }

    private static function compareNumber($a, $b)
    {
        if ($a == $b) {
            return "correct";
        }

        return $a > $b ? "higher" : "lower";
    }

    private static function compareArray($a, $b)
    {
        $a = is_array($a) ? $a : [$a];
        $b = is_array($b) ? $b : [$b];

        $intersecao = array_intersect($a, $b);

        if (count($intersecao) === count($a) && count($intersecao) === count($b)) {
            return "correct";
        }

        if (count($intersecao) > 0) {
            return "partial";
        }

        return "wrong";
    }
}
