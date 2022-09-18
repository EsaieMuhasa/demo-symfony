<?php

namespace App\Controller;

use App\Repository\NewsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * controlleur de l'index du site, visualise gere la page d'accuel et tout les action des utilisateus par defaut
 */
class HomeController extends AbstractController
{

    private NewsRepository $newsRepository;


    public function __construct(NewsRepository $newsRepository)
    {
        $this->newsRepository = $newsRepository;
    }

    #[Route(path:'/{offset<\d+>?0}', name: 'home.index')]
    public function index(int $offset): Response
    {
        $news = $this->newsRepository->findAll(10, $offset * 10);
        $count = $this->newsRepository->countAll();
        return $this->render('home/index.html.twig', [
            'title' => 'mon site bidon des news',
            'pageH1' => 'Liste des news',
            'news' => $news,
            'newsCount' => $count / 10,
            'currentPage' => $offset
        ]);
    }

    /**
     * show news full description
     */
    #[Route(path:'/news-{id}-{slug}', name:'home.show')]
    public function show (int $id) : Response {
        $news = $this->newsRepository->findOneBy(['id' => $id]);

        return $this->render('home/show.html.twig',[
            'news' =>$news,
            'title' => "{$news->getTitle()} - Site bidon",
            'comments' => ''
        ]);;
    }
}
