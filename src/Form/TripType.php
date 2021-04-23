<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Trip;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

use function PHPSTORM_META\type;

class TripType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('poetry', TextareaType::class, [
                'attr' => ['cols' => '75', 'rows' =>'15'],
                'help_html' => true
            ])
            ->add('image', FileType::class, [
                'label' => 'Image',
                'mapped' => false
            ])
            ->add('author')
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'Name'
            ])
            //->add('user', EntityType::class, [
              //  'class' => User::class,
               // 'label' => 'test',
                //'choice_label' => 'userName'
            //])
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
