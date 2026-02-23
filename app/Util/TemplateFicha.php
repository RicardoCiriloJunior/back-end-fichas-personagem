<?php
namespace App\Util;
class TemplateFicha {
    public static array $ficha = [
        'jogador' => '',
        'nome' => '',
        'vida' => [
            'atual' => 0,
            'max' => 0,
        ],
        'classe' => '',
        'raca' => '',
        'mana' => [
            'atual' => 0,
            'max' => 0,
        ],
        'atributos' => [
            'for' => [
                'valor' => 0,
                'modificador' => 0,
            ],
            'agi' => [
                'valor' => 0,
                'modificador' => 0,
            ],
            'int' => [
                'valor' => 0,
                'modificador' => 0,
            ],
            'car' => [
                'valor' => 0,
                'modificador' => 0,
            ],
            'vig' => [
                'valor' => 0,
                'modificador' => 0,
            ],
            'des' => [
                'valor' => 0,
                'modificador' => 0,
            ],
            'srt' => [
                'valor' => 0,
                'modificador' => 0,
            ],
        ],
        'dinheiro' => [
            'ouro' => 0,
            'prata' => 0,
            'cobre' => 0,
        ],
        'reputacao' => [
            'generalistas' => 0,
            'puristas' => 0,
            'karma' => 0,
        ],
        'equipamentos' => [
            'armas' => [
                [
                    'id' => '',
                    'nome' => '',
                    'encantamento' => '',
                    'dano' => '',
                    'custo' => '',
                ],
            ],
            'armaduras' => [
                [
                    'id' => '',
                    'nome' => '',
                    'defesa' => 0,
                ],
            ],
        ],
        'inventario' => [
            [
                'id' => '',
                'nome' => '',
                'quantidade' => 0,
            ],
        ],
        'magias' => [
            [
                'id' => '',
                'efeito' => '',
                'custo' => '',
            ],
        ],
    ];
}
