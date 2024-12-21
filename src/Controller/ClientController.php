<?php

namespace App\Controller;

use App\Repository\ClientRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ClientController extends AbstractController
{


    #[Route('/client', name: 'client_index')]
public function index(ClientRepository $clientRepository): Response
{
    $clients = $clientRepository->findAll();

    return $this->render('client/index.html.twig', [
        'clients' => $clients,
    ]);
}

    #[Route('/client/search', name: 'client_search', methods: ['GET'])]
    public function searchClient(Request $request, ClientRepository $clientRepository): Response
    {
        $telephone = $request->query->get('telephone');
        if (!$telephone) {
            return $this->json(['error' => 'Le numéro de téléphone est requis.'], Response::HTTP_BAD_REQUEST);
        }

        $client = $clientRepository->findOneByTelephone($telephone);

        if (!$client) {
            return $this->json(['error' => 'Client introuvable.'], Response::HTTP_NOT_FOUND);
        }

        return $this->json([
            'id' => $client->getId(),
            'nom' => $client->getNom(),
            'prenom' => $client->getPrenom(),
            'ville' => $client->getVille(),
            'quartier' => $client->getQuartier(),
            'numeroVilla' => $client->getNumeroVilla(),
        ]);
    }
}
