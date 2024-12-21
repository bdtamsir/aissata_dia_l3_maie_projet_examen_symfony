<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\CommandeArticle;
use App\Repository\CommandeRepository;
use App\Repository\ArticleRepository;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CommandeArticleRepository;

class CommandeController extends AbstractController
{
    #[Route('/commande', name: 'commande_index', methods: ['GET'])]
public function index(CommandeRepository $commandeRepository): Response
{
    $commandes = $commandeRepository->findAll();
    return $this->render('commande/index.html.twig', [
        'commandes' => $commandes,
    ]);
}
#[Route('/commande/create', name: 'commande_create', methods: ['GET', 'POST'])]
public function createCommande(
    Request $request,
    ClientRepository $clientRepository,
    ArticleRepository $articleRepository,
    EntityManagerInterface $entityManager
): Response {
    // Récupération des articles pour le formulaire (GET)
    $articles = $articleRepository->findAll();

    // Si méthode GET, afficher le formulaire vide
    if ($request->isMethod('GET')) {
        return $this->render('commande/create.html.twig', [
            'articles' => $articles,
            'articles_data' => [],
        ]);
    }

    // Récupération des données du formulaire (POST)
    $clientId = $request->request->get('client_id');
    $articlesData = $request->request->all()['articles'] ?? []; // Récupérer les articles envoyés

    // Validation des données
    if (!$clientId || empty($articlesData)) {
        $this->addFlash('error', 'Veuillez fournir un client et au moins un article.');
        return $this->redirectToRoute('commande_create');
    }

    // Récupération du client
    $client = $clientRepository->find($clientId);
    if (!$client) {
        $this->addFlash('error', 'Le client spécifié est introuvable.');
        return $this->redirectToRoute('commande_create');
    }

    // Création de la commande
    $commande = new Commande();
    $commande->setClient($client);
    $commande->setDateCommande(new \DateTime());

    // Traitement des articles
    foreach ($articlesData as $articleData) {
        if (!isset($articleData['id'], $articleData['quantite'], $articleData['prix'])) {
            $this->addFlash('error', 'Chaque article doit contenir un ID, une quantité et un prix.');
            return $this->redirectToRoute('commande_create');
        }

        $article = $articleRepository->find($articleData['id']);
        if (!$article) {
            $this->addFlash('error', "L'article avec l'ID {$articleData['id']} est introuvable.");
            return $this->redirectToRoute('commande_create');
        }

        // Vérification du stock disponible
        if ($article->getQuantiteDisponible() < $articleData['quantite']) {
            $this->addFlash('error', "La quantité demandée pour l'article {$article->getNom()} dépasse le stock disponible.");
            return $this->redirectToRoute('commande_create');
        }

        // Création de CommandeArticle
        $commandeArticle = new CommandeArticle();
        $commandeArticle->setCommande($commande);
        $commandeArticle->setArticle($article);
        $commandeArticle->setQuantite($articleData['quantite']);
        $commandeArticle->setPrix($articleData['prix']);

        // Mise à jour du stock disponible
        $article->setQuantiteDisponible($article->getQuantiteDisponible() - $articleData['quantite']);

        $entityManager->persist($commandeArticle);
        $entityManager->persist($article);
    }

    // Sauvegarde de la commande
    $entityManager->persist($commande);
    $entityManager->flush();

    $this->addFlash('success', 'Commande ajoutée avec succès.');
    return $this->redirectToRoute('commande_index');
}


    #[Route('/commande/{commandeId}/article/{articleId}/update', name: 'commande_article_update', methods: ['PUT'])]
    public function updateCommandeArticle(
        int $commandeId,
        int $articleId,
        Request $request,
        CommandeArticleRepository $commandeArticleRepository,
        EntityManagerInterface $entityManager
    ): Response {
        $commandeArticle = $commandeArticleRepository->findOneBy([
            'commande' => $commandeId,
            'article' => $articleId,
        ]);

        if (!$commandeArticle) {
            return $this->json(['error' => 'Article dans la commande introuvable.'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        if (isset($data['quantite'])) {
            $commandeArticle->setQuantite($data['quantite']);
        }

        if (isset($data['prix'])) {
            $commandeArticle->setPrix($data['prix']);
        }

        $entityManager->flush();

        return $this->json([
            'message' => 'Article dans la commande mis à jour avec succès.',
            'commandeArticle' => [
                'id' => $commandeArticle->getId(),
                'quantite' => $commandeArticle->getQuantite(),
                'prix' => $commandeArticle->getPrix(),
            ],
        ]);
    }

    #[Route('/commande/{commandeId}/article/{articleId}/delete', name: 'commande_article_delete', methods: ['DELETE'])]
    public function deleteCommandeArticle(
        int $commandeId,
        int $articleId,
        CommandeArticleRepository $commandeArticleRepository,
        EntityManagerInterface $entityManager
    ): Response {
        $commandeArticle = $commandeArticleRepository->findOneBy([
            'commande' => $commandeId,
            'article' => $articleId,
        ]);

        if (!$commandeArticle) {
            return $this->json(['error' => 'Article dans la commande introuvable.'], Response::HTTP_NOT_FOUND);
        }

        $entityManager->remove($commandeArticle);
        $entityManager->flush();

        return $this->json(['message' => 'Article supprimé de la commande avec succès.']);
    }
}
