<?php

namespace App\Form;

use App\Entity\Receiving;
use App\Entity\Worker;
use App\Repository\WorkerRepository;
use DateTime;
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
                },
                'label' => 'Работник',
                'choice_attr' => function ($choice, $key, $value) use ($options)
                {
                    if ($choice->getName() == $options['worker_name_value'])
                        return ['selected' => true];
                    else
                        return [];
                },
            ])
            ->add('workClothing', TextType::class, [
                'mapped' => \false,
                'label' => 'Идентификатор спецодежды',
                'data' => $options['workClothing_value'],
            ])
            ->add('date', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Дата получения',
                'data' => DateTime::createFromFormat('Y/m/d', $options['date_value'] ?? date('Y/m/d')),
            ])
            ->add('signature', TextType::class, [
                'label' => 'Подпись',
                'data' => $options['signature_value'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Receiving::class,
            'worker_name_value' => null,
            'workClothing_value' => null,
            'date_value' => null,
            'signature_value' => null,
        ]);
    }
}
