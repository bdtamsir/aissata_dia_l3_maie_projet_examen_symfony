<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{


    #[Route('/article', name: 'article_index')]
public function index(ArticleRepository $articleRepository): Response
{
    $articles = $articleRepository->findAll();

    return $this->render('article/index.html.twig', [
        'articles' => $articles,
    ]);
}

    #[Route('/article/list', name: 'article_list', methods: ['GET'])]
    public function listArticles(ArticleRepository $articleRepository): Response
    {
        $articles = $articleRepository->findAll();

        $data = [];
        foreach ($articles as $article) {
            $data[] = [
                'id' => $article->getId(),
                'nom' => $article->getNom(),
                'prixUnitaire' => $article->getPrixUnitaire(),
                'quantiteDisponible' => $article->getQuantiteDisponible(),
            ];
        }

        return $this->json($data);
    }
}
