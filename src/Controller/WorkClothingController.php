<?php

namespace App\Controller;

use App\Entity\WorkClothing;
use App\Form\WorkClothingType;
use App\Repository\WorkClothingRepository;
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

#[Route('/clothing')]
class WorkClothingController extends AbstractController
{
    #[Route('/list', name: 'clothing-list', methods: ['GET'])]
    #[Menu(title: 'Спецодежда', order: 5, role: 'ROLE_CLOTHING')]
    public function index(WorkClothingRepository $workClothingRepository): Response
    {
        $clothing = $workClothingRepository->findAll();
        $table = [];
        foreach ($clothing as $item)
        {
            $table[] = [
                [$item->getId(), ['data-tag' => 'id']],
                [$item->getType(), ['data-tag' => 'type']],
                [$item->getPrice(), ['data-tag' => 'price']],
                [$item->getWearTime(), ['data-tag' => 'wearTime']],
            ];
        }
        $headers = ['Идентификатор', 'Вид', 'Цена', 'Время носки'];
        $m = new MenuCreator;
        return $this->render('table.html.twig', [
            'title' => 'Спецодежда',
            'table' => $table,
            'headers' => $headers,
            'menu' => $m->getMenu('clothing-list'),
        ]);
    }

    #[Route('/add', name: 'clothing-add')]
    public function add(Request $request, EntityManagerInterface $em): Response
    {
        $clothing = new WorkClothing;
        $form = $this->createForm(WorkClothingType::class, $clothing)
            ->add('submit', SubmitType::class, ['label' => 'Отправить'])
        ;

        $err = null;

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $em->beginTransaction();
            try
            {
                $clothing = $form->getData();
                $em->persist($clothing);
                $em->flush();
                $em->commit();
                return $this->redirectToRoute('clothing-add');
            }
            catch (Exception $e)
            {
                $em->rollBack();
                $err = $e->getMessage();
            }
        }

        $m = new MenuCreator;
        return $this->render('form.html.twig', [
            'title' => 'Добавить спецодежду',
            'menu' => $m->getMenu('clothing-list'),
            'form' => $form,
            'error' => $err,
        ]);
    }

    #[Route('/request', name: 'clothing-request', methods: ['POST'])]
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
        $dqb->delete(WorkClothing::class, 'c')
            ->where('c.id in (:arr)')
        ;

        $req = \json_decode($request->getContent(), \true);

        if ($req['add']['status'])
        {
            foreach ($req['add']['rows'] as $row)
            {
                $id = $row['id'];
                $type = $row['type'];
                $time = $row['time'];
                $price = $row['price'];

                $clothing = new WorkClothing();
                $clothing->setId($id)
                    ->setType($type)
                    ->setWearTime($time)
                    ->setPrice($price);
                $em->persist($clothing);
            }
            $em->flush();
            $em->clear();
        }
        // if ($req['update']['status'])
        // {

        // }
        if ($req['delete']['status'])
        {
            $em->beginTransaction();
            $delIds = $req['delete']['rows'];
            try
            {
                $dqb->getQuery()->execute(["arr" => $delIds]);
                $em->commit();
            }
            catch (Exception $e)
            {
                $em->rollback();
            }
        }

        return $this->json([
            "done" => $status
        ]);
    }
}
