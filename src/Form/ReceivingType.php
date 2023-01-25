<?php

namespace App\Form;

use App\Entity\Receiving;
use App\Entity\Worker;
use App\Repository\WorkerRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReceivingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('worker', EntityType::class, [
                'class' => Worker::class,
                'choice_label' => 'name',
                'query_builder' => function (WorkerRepository $workerRepository)
                {
                    return $workerRepository->createQueryBuilder('r')
                        ->orderBy('r.name', 'ASC');
                }
            ])
            ->add('workClothing', TextType::class, ['mapped' => \false])
            ->add('date', DateType::class, ['widget' => 'single_text'])
            ->add('signature', TextType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Receiving::class,
        ]);
    }
}
