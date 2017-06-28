<?php

namespace OF\CalendarBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use OF\CalendarBundle\Form\questionSatisfType;

use Symfony\Component\Form\Extension\Core\Type\CheckboxType;


class questionnaireType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('resultat1', ChoiceType::class,array('choices'  => array(
        'Satisfaisant' =>3,
        'Assez satisfaisant' => 2,
        'Assez insatisfaisant' => 1,
        'Insatisfaisant' => 0),
        'expanded' => True,
        'multiple' => False,
        'label' => 'Attitude et professionnalisme des conférenciers'))
        ->add('resultat2', ChoiceType::class,array('choices'  => array(
        'Satisfaisant' => 3,
        'Assez satisfaisant' => 2,
        'Assez insatisfaisant' => 1,
        'Insatisfaisant' => 0),
        'expanded' => True,
        'multiple' => False,
        'label' => 'Pédagogie des conférenciers'))
        ->add('resultat3', ChoiceType::class,array('choices'  => array(
        'Satisfaisant' => 3,
        'Assez satisfaisant' => 2,
        'Assez insatisfaisant' => 1,
        'Insatisfaisant' => 0),
        'expanded' => True,
        'multiple' => False,
        'label' => 'Connaissances des conférenciers'))
        ->add('resultat4', ChoiceType::class,array('choices'  => array(
        'Satisfaisant' => 3,
        'Assez satisfaisant' => 2,
        'Assez insatisfaisant' => 1,
        'Insatisfaisant' => 0),
        'expanded' => True,
        'multiple' => False,
        'label' => 'Charisme des conférenciers'))
        ->add('resultat5', ChoiceType::class,array('choices'  => array(
        'Satisfaisant' => 3,
        'Assez satisfaisant' => 2,
        'Assez insatisfaisant' => 1,
        'Insatisfaisant' => 0),
        'expanded' => True,
        'multiple' => False,
        'label' => 'Organisation logistique de la visite'))
        ->add('resultat6', ChoiceType::class,array('choices'  => array(
        'Satisfaisant' => 3,
        'Assez satisfaisant' => 2,
        'Assez insatisfaisant' => 1,
        'Insatisfaisant' => 0),
        'expanded' => True,
        'multiple' => False,
        'label' => 'Durée de la visite'))
        ->add('resultat7', ChoiceType::class,array('choices'  => array(
        'Satisfaisant' => 3,
        'Assez satisfaisant' => 2,
        'Assez insatisfaisant' => 1,
        'Insatisfaisant' => 0),
        'expanded' => True,
        'multiple' => False,
        'label' => 'Appréciation globale'));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'OF\CalendarBundle\Entity\Satisfaction'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'of_calendarbundle_satisfaction';
    }


}
