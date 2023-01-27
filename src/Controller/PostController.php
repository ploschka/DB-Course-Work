<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use App\Service\Menu;
use App\Service\MenuCreator;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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
                [$post->getName(), ['data-tag' => 'name']],
                [$post->getId(), ['data-tag' => 'id']],
                [$post->getDiscount(), ['data-tag' => 'discount']],
            ];
        }
        $headers = ['Название', 'Идентификатор', 'Скидка'];
        $m = new MenuCreator;
        return $this->render('table.html.twig', [
            'title' => 'Должности',
            'table' => $table,
            'headers' => $headers,
            'menu' => $m->getMenu('post-list'),
        ]);
    }

    #[Route('/add', name: 'post-add')]
    public function add(Request $request, EntityManagerInterface $em): Response
    {
        $post = new Post;
        $form = $this->createForm(PostType::class, $post)
            ->add('submit', SubmitType::class, ['label' => 'Отправить'])
        ;

        $err = null;

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $em->beginTransaction();
            try
            {
                $post = $form->getData();
                $em->persist($post);
                $em->flush();
                $em->commit();
                return $this->redirectToRoute('post-add');
            }
            catch (Exception $e)
            {
                $em->rollback();
                $err = $e->getMessage();
            }
        }


        $m = new MenuCreator;
        return $this->render('form.html.twig', [
            'title' => 'Добавить должность',
            'menu' => $m->getMenu('post-list'),
            'form' => $form,
            'error' => $err,
        ]);
    }

    #[Route('/delete', name: 'post-delete', methods: ['POST'])]
    public function delete(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $dqb = $em->createQueryBuilder();
        $dqb->delete(Post::class, 'p')
            ->where('p.id in (:arr)')
        ;

        $req = \json_decode($request->getContent(), \true);
        $status = \true;
        $error = null;

        $em->beginTransaction();
        try
        {
            $delIds = $req;
            $dqb->getQuery()->execute(["arr" => $delIds]);
            $em->commit();
        }
        catch (Exception $e)
        {
            $error = $e->getCode();
            $status = \false;
            $em->rollback();
        }

        return $this->json([
            'done' => $status,
            'error' => $error,
        ]);
    }
}
