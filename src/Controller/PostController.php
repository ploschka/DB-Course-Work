<?php

namespace App\Controller;

use App\Repository\PostRepository;
use App\Service\Menu;
use App\Service\MenuCreator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/post')]
class PostController extends AbstractController
{
    #[Route('/list', name: 'post-list', methods: ['GET'])]
    #[Menu(title: 'Должности')]
    public function index(PostRepository $postRepository): Response
    {
        $posts = $postRepository->findAll();
        $table = [];
        foreach ($posts as $post)
        {
            $table[] = [
                $post->getName(),
                $post->getDiscount(),
            ];
        }
        $headers = ['Название', 'Скидка'];
        $m = new MenuCreator;
        return $this->render('table.html.twig', [
            'title' => 'Должности',
            'table' => $table,
            'headers' => $headers,
            'menu' => $m->getMenu('post-list'),
        ]);
    }

    #[Route('/request', name: 'post-request', methods: ['POST'])]
    public function request(Request $request): JsonResponse
    {
        return $this->json([
            
        ]);
    }
}
