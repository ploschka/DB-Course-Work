<?php

namespace App\Form;

use App\Entity\Department;
use App\Entity\Post;
use App\Entity\Worker;
use App\Repository\DepartmentRepository;
use App\Repository\PostRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WorkerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'ФИО',
                'data' => $options['name_value'],
            ])
            ->add('department', EntityType::class, [
                'class' => Department::class,
                'choice_label' => 'name',
                'query_builder' => function (DepartmentRepository $departmentRepository)
                {
                    return $departmentRepository->createQueryBuilder('d')
                        ->orderBy('d.name', 'ASC');
                },
                'label' => 'Цех',
                'choice_attr' => function ($choice, $key, $value) use ($options)
                {
                    if ($choice->getName() == $options['department_value'])
                        return ['selected' => true];
                    else
                        return [];
                }
            ])
            ->add('post', EntityType::class, [
                'class' => Post::class,
                'choice_label' => 'name',
                'query_builder' => function (PostRepository $postRepository)
                {
                    return $postRepository->createQueryBuilder('p')
                        ->orderBy('p.name', 'ASC');
                },
                'label' => 'Должность',
                'choice_attr' => function ($choice, $key, $value) use ($options)
                {
                    if ($choice->getName() == $options['post_value'])
                        return ['selected' => true];
                    else
                        return [];
                }
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Worker::class,
            'name_value' => null,
            'department_value' => null,
            'post_value' => null,
        ]);
    }
}
