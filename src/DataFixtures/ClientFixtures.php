<?php

namespace App\DataFixtures;

use App\Entity\Client;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ClientFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $clients = [
            ['nom' => 'Doe', 'prenom' => 'John', 'telephone' => '0771234567', 'ville' => 'Paris', 'quartier' => 'Marais', 'numeroVilla' => '12B'],
            ['nom' => 'Smith', 'prenom' => 'Anna', 'telephone' => '0779876543', 'ville' => 'Lyon', 'quartier' => 'Part-Dieu', 'numeroVilla' => '34A'],
            ['nom' => 'Brown', 'prenom' => 'Charlie', 'telephone' => '0784561230', 'ville' => 'Marseille', 'quartier' => 'Vieux-Port', 'numeroVilla' => '45C'],
            ['nom' => 'Taylor', 'prenom' => 'Lucy', 'telephone' => '0767890123', 'ville' => 'Toulouse', 'quartier' => 'Capitole', 'numeroVilla' => '78D'],
            ['nom' => 'Wilson', 'prenom' => 'Emily', 'telephone' => '0750123456', 'ville' => 'Nice', 'quartier' => 'Promenade', 'numeroVilla' => '99E'],
        ];

        foreach ($clients as $data) {
            $client = new Client();
            $client->setNom($data['nom']);
            $client->setPrenom($data['prenom']);
            $client->setTelephone($data['telephone']);
            $client->setVille($data['ville']);
            $client->setQuartier($data['quartier']);
            $client->setNumeroVilla($data['numeroVilla']);
            $manager->persist($client);
        }

        $manager->flush();
    }
}
