<?php

namespace App\Controller\Admin;

use App\Entity\News;
use App\Form\NewsType;
use App\Repository\NewsRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path:'/admin')]
class AdminController extends AbstractController
{
    private NewsRepository $newsRepository;
    private EntityManagerInterface $entityManager;

    public function __construct (NewsRepository $newsRepository, EntityManagerInterface $entityManager)
    {
        $this->newsRepository = $newsRepository;
        $this->entityManager = $entityManager;
    }

    #[Route(path : '/{offset<\d+>?0}', name: 'admin.index')]
    public function index(int $offset): Response
    {
        $countNews = $this->newsRepository->countAll();
        $listNews = $this->newsRepository->findAll(10, $offset * 10);

        return $this->render('admin/admin/index.html.twig', [
            'countNews' => $countNews / 10,
            'listNews' => $listNews,
            'currentPage' => $offset,
            'navItem' => 'admin.index'
        ]);
    }

    #[Route(path:'/insert-news', name:'admin.insertNews')]
    public function insertNews (Request $request) : Response {
        $news = new News();
        $news->setAuthor("Ing. Esaie MUHASA");
        $form = $this->createForm(NewsType::class, $news);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $news->setRecordingDate(new DateTime());
            $this->entityManager->persist($news);
            $this->entityManager->flush();
            return $this->redirectToRoute('admin.index', ['offset' => 0]);
        }

        return $this->render('admin/admin/insertNews.html.twig', [
            'form' => $form->createView(),
            'navItem' => 'admin.insertNews'
        ]);
    }

    #[Route(path : "/news-{id<\d+>}-{slug}/update", name:"admin.updateNews")]
    public function updateNews (int $id, string $slug, Request $request) : Response {
        $news = $this->newsRepository->findById($id);
        $form = $this->createForm(NewsType::class, $news);

        $form->handleRequest($request);
        if( $form->isSubmitted() && $form->isValid()) {
            $news->setLastUpdateDate(new DateTime());
            $this->entityManager->flush();
            return $this->redirectToRoute('admin.index');
        }

        return $this->render('admin/admin/updateNews.html.twig', [
            'form' => $form->createView(),
            'news' => $news
        ]);
    }
    
    #[Route(path : "/news-{id<\d+>}-{slug}/delete", name:"admin.deleteNews")]
    public function deleteNews (int $id, string $slug) : Response {
        
        return $this->redirectToRoute('admin.inde');
    }
}
