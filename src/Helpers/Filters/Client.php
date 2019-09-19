<?php


namespace App\Helpers\Filters;

//use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

//class Client extends AbstractFilters
use App\Repository\EntityTableRepository;

class Client extends EntityTableRepository
{
    public static $filterFields = [
        'client' => [
            'fromUmcs' => [
                [
                    'label' => 'Z UMCS',
                    'type' => 'checkbox',
                    'value' => 1
                ],
                [
                    'label' => 'Spoza UMCS',
                    'type' => 'checkbox',
                    'value' => 0
                ],
            ],
        ]
    ];

    public static $columns = [
        'client_name' => [
            'label' => 'Nazwa',
            'sort' => true,
        ],
        'client_locality' => [
            'label' => 'Miejscowość',
            'sort' => true,
        ],
        'client_fromUmcs' => [
            'label' => 'Z UMCS',
            'sort' => true,
        ],
    ];
}