<?php

namespace App\Controller;

use App\Entity\Receiving;
use App\Entity\WorkClothing;
use App\Entity\Worker;
use App\Form\ReceivingType;
use App\Repository\ReceivingRepository;
use App\Service\Menu;
use App\Service\MenuCreator;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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
                $receiving->getWorkClothing()->getId(),
                $receiving->getWorkClothing()->getType(),
                $receiving->getDate()->format('Y/m/d'),
            ];
        }
        $headers = ['ФИО работника', 'Идентификатор спецодежды', 'Вид спецодежды', 'Дата'];
        $m = new MenuCreator;
        return $this->render('table.html.twig', [
            'title' => 'Получения',
            'table' => $table,
            'headers' => $headers,
            'menu' => $m->getMenu('receiving-list'),
        ]);
    }

    #[Route('/add', name: 'receiving-add')]
    public function add(Request $request, EntityManagerInterface $em): Response
    {
        $receiving = new Receiving;
        $form = $this->createForm(ReceivingType::class, $receiving)
            ->add('submit', SubmitType::class)
        ;

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $receiving = $form->getData();
            $clothingId = $form->get('workClothing')->getData();
            $clothing = $em->find(Receiving::class, $clothingId);
            if (\is_null($clothing))
            {
                $receiving->setWorkClothing($em->find(WorkClothing::class, $clothingId));
                $em->persist($receiving);
                $em->flush();
                return $this->redirectToRoute('receiving-add');
            }
        }

        $m = new MenuCreator;
        return $this->render('form.html.twig', [
            'title' => 'Добавить получение',
            'menu' => $m->getMenu('receiving-list'),
            'form' => $form,
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
