<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Trip;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use function PHPSTORM_META\type;

class TripType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('poetry')
            ->add('image', FileType::class, [
                'label' => 'Image',
                'mapped' => false
            ])
            ->add('author')
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'Name'
            ])
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Trip::class,
        ]);
    }
}
