<?php

namespace App\Form;

use App\Entity\Department;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DepartmentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        if ($options['id_field'])
        {
            $builder->add('id', IntegerType::class, [
                'disabled' => true,
                'data' => $options['id_value'],
            ]);
        }

        $builder
            ->add('name', TextType::class, [
                'label' => 'Название',
                'data' => $options['name_value'],
            ])
            ->add('chief_name', TextType::class, [
                'label' => 'ФИО начальника',
                'data' => $options['chief_name_value'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Department::class,
            'name_value' => null,
            'chief_name_value' => null,
            'id_field' => false,
            'id_value' => null,
        ]);
    }
}
