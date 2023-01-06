<?php

namespace App\Controller;

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
    public function request(Request $request): JsonResponse
    {
        return $this->json([
            
        ]);
    }
}
