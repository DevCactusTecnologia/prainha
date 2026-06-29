<?php

namespace App\Models\Home;

class Campaign
{
    const CAMPAIGNS_MONTHS = [
        [
            'month' => '1', 
            'name' => 'Janeiro Branco',
            'url_image' => 'assets/images/home/janeiro-branco.jpg'
        ],
        [
            'month' => '2', 
            'name' => 'Fevereiro Roxo',
            'url_image' => 'assets/images/home/fevereiro-roxo.jpg'
        ],
        [
            'month' => '3', 
            'name' => 'Março Azul-Marinho',
            'url_image' => 'assets/images/home/marco-azul-marinho.jpg'
        ],
        [
            'month' => '4', 
            'name' => 'Abril Verde-Azul',
            'url_image' => 'assets/images/home/abril-verde-azul.jpg'
        ],
        [
            'month' => '5', 
            'name' => 'Maio Amarelo',
            'url_image' => 'assets/images/home/maio-amarelo.jpg'
        ],
        [
            'month' => '6', 
            'name' => 'Junho Vermelho-Laranja',
            'url_image' => 'assets/images/home/junho-vermelho-laranja.jpg'
        ],
        [
            'month' => '7', 
            'name' => 'Julho Verde-Amarelo',
            'url_image' => 'assets/images/home/julho-verde-amarelo.jpg'
        ],
        [
            'month' => '8', 
            'name' => 'Agosto Dourado',
            'url_image' => 'assets/images/home/agosto-dourado.jpg'
        ],
        [
            'month' => '9', 
            'name' => 'Setembro Amarelo',
            'url_image' => 'assets/images/home/setembro-amarelo.jpg'
        ],
        [
            'month' => '10', 
            'name' => 'Outubro Rosa',
            'url_image' => 'assets/images/home/outubro-rosa.jpg'
        ],
        [
            'month' => '11', 
            'name' => 'Novembro Azul',
            'url_image' => 'assets/images/home/novembro-azul.jpg'
        ],
        [
            'month' => '12', 
            'name' => 'Dezembro Laranja',
            'url_image' => 'assets/images/home/dezembro-laranja.jpg'
        ]
    ];

    public static function monthCurrent()
    {
        return (object) collect(self::CAMPAIGNS_MONTHS)
            ->firstWhere('month', date('m'));
    }
    
}
