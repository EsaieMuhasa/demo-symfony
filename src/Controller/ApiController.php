<?php

namespace App\Controller;

use App\Entity\News;
use App\Repository\NewsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route(path:'/api')]
class ApiController extends AbstractController
{
    #[Route('/news/', name: 'api.news.all', methods:['GET'])]
    public function index(NewsRepository $newsRepository, SerializerInterface $serializer): Response
    {
        $news = $newsRepository->findAll();

        $data = $serializer->serialize($news, 'json');

        return new JsonResponse($data, JsonResponse::HTTP_OK, [], true);
    }

    #[Route('/news/{id<\d+>}', name: 'api.news.show', methods:['GET'])]
    public function getNews (News $news, SerializerInterface $serializer) : Response {
        $data = $serializer->serialize($news, 'json');

        return new JsonResponse($data, JsonResponse::HTTP_OK, [], true);
    }

    #[Route('/news/{id<\d+>}', name: 'api.news.delete', methods:['DELETE'])]
    public function deleteNews (News $news, EntityManagerInterface $entityManager) : Response {
        $entityManager->remove($news);
        $entityManager->flush();

        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT, [], false);
    }
}
