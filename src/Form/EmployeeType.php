<?php

namespace App\Form;

use App\Entity\Employee;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmployeeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, [
                'label' => 'Nom'
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Prenom'
            ])
            ->add('email', TextType::class, [
                'label' => 'Email'
            ])
            ->add('entryDate', null, [
                'widget' => 'single_text',
                'label' => 'Date d\'entrée'
            ])
            ->add('status', TextType::class, [
                'label' => 'Statut'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Employee::class,
        ]);
    }
}
