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

    #[Route('/delete', name: 'clothing-delete', methods: ['POST'])]
    public function delete(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $dqb = $em->createQueryBuilder();
        $dqb->delete(WorkClothing::class, 'c')
            ->where('c.id in (:arr)')
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
