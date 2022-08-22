<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * controlleur de l'index du site, visualise gere la page d'accuel et tout les action des utilisateus par defaut
 */
class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'title' => 'mon site bidon des news',
            'pageH1' => 'Liste des news'
        ]);
    }
}
