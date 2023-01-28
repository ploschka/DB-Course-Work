<?php

namespace App\Form;

use App\Entity\WorkClothing;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WorkClothingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('id', TextType::class, [
                'label' => 'Идентификатор',
                'data' => $options['id_value'],
                'disabled' => $options['id_field']
            ])
            ->add('type', TextType::class, [
                'label' => 'Вид',
                'data' => $options['type_value'],
            ])
            ->add('price', NumberType::class, [
                'label' => 'Цена',
                'data' => $options['price_value'],
            ])
            ->add('wearTime', IntegerType::class, [
                'label' => 'Время носки',
                'data' => $options['wearTime_value'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => WorkClothing::class,
            'id_value' => null,
            'type_value' => null,
            'price_value' => null,
            'wearTime_value' => null,
            'id_field' => false,
        ]);
    }
}
