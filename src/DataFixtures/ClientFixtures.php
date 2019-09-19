<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Client;

class ClientFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $clientArray = [
            [
                'name' => 'Zakład Krystalografii',
                'street' => 'pl. Marii Curie-Skłodowskiej',
                'homeNumber' => 5,
                'postCode' => '20-838',
                'locality' => 'Lublin',
                'fromUmcs' => true,
            ],
            [
                'name' => 'Zakład Chemii Teoretycznej',
                'street' => 'pl. Marii Curie-Skłodowskiej',
                'homeNumber' => 5,
                'postCode' => '20-838',
                'locality' => 'Lublin',
                'fromUmcs' => true,
            ],
            [
                'name' => 'Instytut Technologii Materiałów Elektronicznych',
                'street' => 'Wólczyńska',
                'homeNumber' => 133,
                'postCode' => '01-919',
                'locality' => 'Warszawa',
                'fromUmcs' => false,
            ],
        ];

        foreach ($clientArray as $singleClient) {
            $client = new Client();
            foreach ($singleClient as $key => $value) {
                $setKey = 'set'.ucfirst($key);
                $client->$setKey($value);
            }
            $manager->persist($client);
        }

        $manager->flush();
    }
}
