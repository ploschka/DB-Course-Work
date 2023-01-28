<?php

namespace App\Controller;

use App\Entity\Department;
use App\Entity\Receiving;
use App\Repository\ReceivingRepository;
use App\Service\Menu;
use App\Service\MenuCreator;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReportController extends AbstractController
{
    #[Route('/__report/{year}/{month}', name: 'reportBackend')]
    public function reportBackend(Request $request, int $year, int $month, EntityManagerInterface $em): Response
    {
        $months = [
            ['январь', 31],
            ['февраль', 28],
            ['март', 31],
            ['апрель', 30],
            ['май', 31],
            ['июнь', 30],
            ['июль', 31],
            ['август', 31],
            ['сентябрь', 30],
            ['октябрь', 31],
            ['ноябрь', 30],
            ['декабрь', 31],
        ];

        if ((!($year % 4) && ($year % 100)) || (!($year % 400)))
        {
            $months[1][1] = 29;
        }

        $currMonth = $months[$month - 1];

        $from = DateTime::createFromFormat('Y/m/d', "$year/$month/01");
        $to = DateTime::createFromFormat('Y/m/d', "$year/$month/{$currMonth[1]}");

        $qb = $em->createQueryBuilder();
        $qb->select('r', 'c', 'w', 'd', 'p')
            ->from(Receiving::class, 'r')
            ->where('r.date >= :fr')
            ->andWhere('r.date <= :to')
            ->innerJoin('r.workClothing', 'c')
            ->innerJoin('r.worker', 'w')
            ->innerJoin('w.department', 'd')
            ->innerJoin('w.post', 'p')
            ->orderBy('d.name', 'ASC')
            ->addOrderBy('w.name', 'ASC')
            ->addOrderBy('c.type', 'ASC');

        $results = $qb->getQuery()->setParameters(['fr' => $from, 'to' => $to])->getResult();


        $table = [];
        $dep = null;
        $total = 0;
        $depTotal = 0;
        foreach ($results as $rec)
        {
            $work = $rec->getWorker();

            if ($dep !== $work->getDepartment())
            {
                if ($depTotal > 0)
                {
                    $table[] = [$depTotal];
                    $total += $depTotal;
                    $depTotal = 0;
                }
                $dep = $work->getDepartment();
                $table[] = [$dep->getName()];
            }

            $cloth = $rec->getWorkClothing();
            $price = $cloth->getPrice();
            $disc = $work->getPost()->getDiscount();
            $priceWithDiscount = $price - ($price * $disc / 100);
            $depTotal += $priceWithDiscount;
            $table[] = [
                $work->getName(),
                $cloth->getType(),
                $price,
                $disc,
                $priceWithDiscount,
            ];
        }

        $total += $depTotal;
        $table[] = [$depTotal];
        $table[] = [$total];

        $headers = ['Ф.И.О. работника', 'Вид спецодежды', 'Стоимость единицы, тыс. руб.', 'Скидка %', 'Стоимость с учётом скидки, тыс. руб.'];
        $m = new MenuCreator;
        return $this->render('tableonly.html.twig', [
            'title' => "Отчёт о получении спецодежды по заводу за {$currMonth[0]} {$year}",
            'table' => $table,
            'headers' => $headers,
        ]);
    }

    #[Route('/report', name: 'report')]
    #[Menu(title: 'Отчёт', order: 7, role: 'ROLE_REPORT')]
    public function report(Request $request, ReceivingRepository $receivingRepository): Response
    {
        $months = [
            'Январь',
            'Февраль',
            'Март',
            'Апрель',
            'Май',
            'Июнь',
            'Июль',
            'Август',
            'Сентябрь',
            'Октябрь',
            'Ноябрь',
            'Декабрь',
        ];

        $recs = $receivingRepository->createQueryBuilder('r')->orderBy('r.date', 'ASC')->getQuery()->getResult();
        $choices = [];
        foreach ($recs as $r)
        {

            $arr = \explode('/', $r->getDate()->format('Y/m/d'));
            $year = (int)($arr[0]);
            $month = (int)($arr[1]) - 1;
            $date = "{$year} {$months[$month]}";
            $choices[$date] = \json_encode([
                'year' => $year,
                'month' => $month + 1,
            ]);
        }

        $form = $this->createFormBuilder()
            ->add('month', ChoiceType::class, [
                'choices' => $choices,
                'label' => \false,
            ])
            ->getForm();
        $m = new MenuCreator;
        return $this->render('report.html.twig', [
            'title' => 'Отчёт',
            'menu' => $m->getMenu('report'),
            'form' => $form,
        ]);
    }
}
