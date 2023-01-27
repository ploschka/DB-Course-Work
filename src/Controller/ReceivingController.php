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
use Exception;
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
            ->innerJoin('r.workClothing', 'c');
        $receivings = $qb->getQuery()->getResult();

        $table = [];
        foreach ($receivings as $receiving)
        {
            $table[] = [
                [$receiving->getWorker()->getName(), ['data-tag' => 'worker_name']],
                [$receiving->getWorkClothing()->getId(), ['data-tag' => 'id']],
                [$receiving->getWorkClothing()->getType(), ['data-tag' => 'clothing_type']],
                [$receiving->getDate()->format('Y/m/d'), ['data-tag' => 'date']],
                [$receiving->getSignature(), ['data-tag' => 'signature']],
            ];
        }
        $headers = ['ФИО работника', 'Идентификатор спецодежды', 'Вид спецодежды', 'Дата', 'Подпись'];
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
            ->add('submit', SubmitType::class, ['label' => 'Отправить'])
        ;

        $err = null;

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $em->beginTransaction();
            try
            {
                $receiving = $form->getData();
                if (\is_null($receiving->getWorkClothing()->getReceiving()))
                {
                    $em->persist($receiving);
                    $em->flush();
                    $em->commit();
                    return $this->redirectToRoute('receiving-add');
                }
                else
                {
                    $em->rollback();
                    $err = 'Получение спецодежды с данным номером уже создано';
                }
            }
            catch (Exception $e)
            {
                $em->rollback();
                $err = $e->getMessage();
            }
        }

        $m = new MenuCreator;
        return $this->render('form.html.twig', [
            'title' => 'Добавить получение',
            'menu' => $m->getMenu('receiving-list'),
            'form' => $form,
            'error' => $err,
        ]);
    }

    #[Route('/delete', name: 'receiving-delete', methods: ['POST'])]
    public function delete(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $dqb = $em->createQueryBuilder();
        $dqb->delete(Receiving::class, 'r')
            ->where('r.workClothing in (:arr)')
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
