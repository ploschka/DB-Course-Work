<?php

namespace App\Controller;

use App\Entity\Receiving;
use App\Entity\WorkClothing;
use App\Entity\Worker;
use App\Service\Menu;
use App\Service\MenuCreator;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/receiving')]
class ReceivingController extends AbstractController
{
    #[Route('/list', name: 'receiving-list', methods: ['GET'])]
    #[Menu(title: 'Получения', order: 6, role: 'ROLE_RECEIVING')]
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
                $receiving->getDate()->format('d/m/Y'),
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
    public function request(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $status = \true;

        // $uqb = $em->createQueryBuilder();
        // $uqb->update(Receiving::class, 'r')
        //     ->set('r.worker', ':worker')
        //     ->set('r.date', ':date')
        //     ->where('r.workClothing = :clothing')
        // ;

        $dqb = $em->createQueryBuilder();
        $dqb->delete(Receiving::class, 'r')
            ->where('r.workClothing in (:arr)')
        ;

        $req = \json_decode($request->getContent(), \true);

        if ($req['add']['status'])
        {
            foreach ($req['add']['rows'] as $row)
            {
                $worker = $em->find(Worker::class, $row['worker']);
                $clothing = $em->find(WorkClothing::class, $row['clothing']);
                $date = DateTime::createFromFormat('d/m/Y', $row['date']);
                $signature = $row['signature'];

                $receiving = new Receiving();
                $receiving->setWorker($worker)
                          ->setWorkClothing($clothing)
                          ->setDate($date)
                          ->setSignature($signature)
                ;
                $em->persist($receiving);
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
