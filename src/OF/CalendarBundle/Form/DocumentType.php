<?php

// src/OC/PlatformBundle/Form/DocumentType.php


namespace OF\CalendarBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class DocumentType extends AbstractType

{

  public function buildForm(FormBuilderInterface $builder, array $options)

  {

    $builder
    ->add('file', FileType::class)
    ->add('title', TextType::class)
     ->add('category', ChoiceType::class, array(
        'choices'  => array(
            'Technique' => "Technique",
            'Déroulement visite' => "Visite",
            'Déroulement de la procédure' => "Procedure",
            'Admin' => "Admin",
            'Autre' => "Autre",
    )))
    ->add('save', SubmitType::class)

    ;

  }

}