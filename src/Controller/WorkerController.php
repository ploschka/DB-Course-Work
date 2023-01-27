<?php

namespace App\Controller;

use App\Entity\Department;
use App\Entity\Post;
use App\Entity\Worker;
use App\Form\WorkerType;
use App\Repository\DepartmentRepository;
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

#[Route('/worker')]
class WorkerController extends AbstractController
{
    #[Route('/list', name: 'worker-list', methods: ['GET'])]
    #[Menu(title: 'Работники', order: 3, role: 'ROLE_WORKER')]
    public function table(EntityManagerInterface $em): Response
    {
        $qb = $em->createQueryBuilder();
        $qb->select('w', 'd', 'p')
            ->from(Worker::class, 'w')
            ->innerJoin('w.department', 'd')
            ->innerJoin('w.post', 'p');
        $workers = $qb->getQuery()->getResult();

        $table = [];
        foreach ($workers as $worker)
        {
            $table[] = [
                [$worker->getName(), ['data-tag' => 'name']],
                [$worker->getId(), ['data-tag' => 'id']],
                [$worker->getDepartment()->getName(), ['data-tag' => 'department']],
                [$worker->getPost()->getName(), ['data-tag' => 'post']],
            ];
        }
        $headers = ['ФИО', 'Идентификатор', 'Цех', 'Должность'];
        $m = new MenuCreator;
        return $this->render('table.html.twig', [
            'title' => 'Работники',
            'table' => $table,
            'headers' => $headers,
            'menu' => $m->getMenu('worker-list'),
        ]);
    }

    #[Route('/add', name: 'worker-add')]
    public function add(Request $request, EntityManagerInterface $em): Response
    {
        $worker = new Worker;
        $form = $this->createForm(WorkerType::class, $worker)
            ->add('submit', SubmitType::class, ['label' => 'Отправить'])
        ;

        $err = null;

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $em->beginTransaction();
            try
            {
                $worker = $form->getData();
                $em->persist($worker);
                $em->flush();
                $em->commit();
                return $this->redirectToRoute('worker-add');
            }
            catch (Exception $e)
            {
                $em->rollback();
                $err = $e->getMessage();
            }
        }

        $m = new MenuCreator;
        return $this->render('form.html.twig', [
            'title' => 'Добавить работника',
            'menu' => $m->getMenu('worker-list'),
            'form' => $form,
            'error' => $err,
        ]);
    }

    #[Route('/request', name: 'worker-request', methods: ['POST'])]
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
        $dqb->delete(Worker::class, 'w')
            ->where('w.id in (:arr)')
        ;

        $req = \json_decode($request->getContent(), \true);

        if ($req['add']['status'])
        {
            foreach ($req['add']['rows'] as $row)
            {
                $post = $em->find(Post::class, $row['post']);
                $department = $em->find(Department::class, $row['department']);
                $name = $row['name'];

                $worker = new Worker();
                $worker->setPost($post)
                    ->setDepartment($department)
                    ->setName($name);
                $em->persist($worker);
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
