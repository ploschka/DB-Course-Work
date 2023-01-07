<?php

namespace App\Controller;

use App\Entity\Department;
use App\Entity\Post;
use App\Entity\Worker;
use App\Service\Menu;
use App\Service\MenuCreator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/worker')]
class WorkerController extends AbstractController
{
    #[Route('/list', name: 'worker-list', methods: ['GET'])]
    #[Menu(title: 'Работники')]
    public function table(EntityManagerInterface $em): Response
    {
        $qb = $em->createQueryBuilder();
        $qb->select('w', 'd', 'p')
           ->from(Worker::class, 'w')
           ->innerJoin('w.department', 'd')
           ->innerJoin('w.post', 'p')
        ;
        $workers = $qb->getQuery()->getResult();
        
        $table = [];
        foreach ($workers as $worker)
        {
            $table[] = [
                $worker->getName(),
                $worker->getDepartment()->getName(),
                $worker->getPost()->getName(),
            ];
        }
        $headers = ['ФИО', 'Цех', 'Должность'];
        $m = new MenuCreator;
        return $this->render('table.html.twig', [
            'title' => 'Работники',
            'table' => $table,
            'headers' => $headers,
            'menu' => $m->getMenu('worker-list'),
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
                         ->setName($name)
                ;
                $em->persist($worker);
            }
            $em->flush();
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
