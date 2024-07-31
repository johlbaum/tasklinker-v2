<?php

namespace App\Form;

use App\Entity\Employee;
use App\Entity\Status;
use App\Entity\Task;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // Objectif : Restreindre la liste des employés sélectionnables pour une tâche aux seuls employés 
        // participant au projet auquel la tâche est rattachée.
        $projectEmployees = $options['projectEmployees'];

        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre de la tâche'
            ])
            ->add('description', TextType::class, [
                'label' => 'Description'
            ])
            ->add('deadline', null, [
                'widget' => 'single_text',
                'label' => 'Date'
            ])
            ->add('status', EntityType::class, [
                'class' => Status::class,
                'choice_label' => 'label',
                'label' => 'Statut'
            ])
            ->add('employee', EntityType::class, [
                'class' => Employee::class,
                'choice_label' => 'firstName',
                'label' => 'Membre',
                'choices' => $projectEmployees
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Task::class, 'projectEmployees' => null,
        ]);
    }
}
