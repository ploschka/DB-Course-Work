<?php

namespace App\Controller;

use App\Entity\Receiving;
use App\Service\Menu;
use App\Service\MenuCreator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/receiving')]
class ReceivingController extends AbstractController
{
    #[Route('/list', name: 'receiving-list', methods: ['GET'])]
    #[Menu(title: 'Получения')]
    public function index(EntityManagerInterface $em): Response
    {        
        $qb = $em->createQueryBuilder();
        $qb->select('r', 'w', 'c')
           ->from(Receiving::class, 'r')
           ->innerJoin('r.worker', 'w')
           ->innerJoin('r.workClothing', 'c')
        ;
        $receivings = $qb->getQuery()->getResult();

        $table = [];
        foreach ($receivings as $receiving)
        {
            $table[] = [
                $receiving->getWorker()->getName(),
                $receiving->getWorkClothing()->getType(),
                $receiving->getDate()->format('d/m/Y')
            ];
        }
        $headers = ['ФИО работника', 'Вид спецодежды', 'Дата'];
        $m = new MenuCreator;
        return $this->render('table.html.twig', [
            'title' => 'Получения',
            'table' => $table,
            'headers' => $headers,
            'menu' => $m->getMenu('receiving-list'),
        ]);
    }

    #[Route('/request', name: 'receiving-request', methods: ['POST'])]
    public function request(): JsonResponse
    {
        return $this->json([
            
        ]);
    }
}
