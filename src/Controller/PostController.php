<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use App\Service\Menu;
use App\Service\MenuCreator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/post')]
class PostController extends AbstractController
{
    #[Route('/list', name: 'post-list', methods: ['GET'])]
    #[Menu(title: 'Должности', order: 2, role: 'ROLE_POST')]
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
    public function request(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $status = \true;

        // $uqb = $em->createQueryBuilder();
        // $uqb->update(Department::class, 'd')
        //     ->set('r.worker', ':worker')
        //     ->set('r.date', ':date')
        //     ->where('r.workClothing = :clothing')
        // ;

        $dqb = $em->createQueryBuilder();
        $dqb->delete(Post::class, 'p')
            ->where('p.id in (:arr)')
        ;

        $req = \json_decode($request->getContent(), \true);

        if ($req['add']['status'])
        {
            foreach ($req['add']['rows'] as $row)
            {
                $name = $row['name'];
                $discount = $row['discount'];

                $post = new Post();
                $post->setName($name)
                     ->setDiscount($discount)
                ;
                $em->persist($post);
            }
            $em->flush();
            $em->clear();
        }
        // if ($req['update']['status'])
        // {
            
        // }
        if ($req['delete']['status'])
        {
            $delIds = $req['delete']['rows'];
            $dqb->getQuery()->execute(["arr" => $delIds]);
        }
        
        return $this->json([
            "done" => $status            
        ]);
    }
}
