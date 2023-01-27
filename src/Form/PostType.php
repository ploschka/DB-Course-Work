<?php

namespace App\Form;

use App\Entity\Post;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends AbstractType
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
            ->add('discount', IntegerType::class, [
                'label' => 'Скидка',
                'data' => $options['discount_value'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
            'name_value' => null,
            'discount_value' => null,
            'id_field' => false,
            'id_value' => null,
        ]);
    }
}
