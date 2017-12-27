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
class ElementVisiteType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('titre', ChoiceType::class,array('label' => 'élément de visite' , 'choices'  => array(
        'Extérieurs des bâtiments' => 'Extérieurs des bâtiments',
        'Espace de créativité' => 'Espace de créativité',
        'Centre de Conférence' => 'Centre de Conférence',
        'Rez-de-chaussée Opale' =>'Rez-de-chaussée Opale',
        'Showroom' => 'Showroom',
        'Halle d’essais' => 'Halle d’essais',
        'Restauration'=>'Restauration',


        ), 'attr'  => array('class' => 'titreInput', 'placeholder' => 'Activité'))) // les classes pour le js
        ->add('duree', TimeType::class,array('attr'  => array('class' => 'dureeInput' , 'placeholder' => 'Durée')))
        ->add('thematique', ChoiceType::class,array('label' => 'Thématique' , 'choices'  => array(
        'Clients et territoires' => 'Clients et territoires',
        'Systèmes électriques' => 'Systèmes électriques',
        'Production bas carbone'=>'Production bas carbone',
        'Au coeur de la société'=>'Au coeur de la société',
        'Présentation de la R&D' => 'Présentation de la R&D',
        'Présentation du plateau de Saclay' => 'Présentation du plateau de Saclay',
        'Showroom complet' => 'Showroom complet')))
        ->add('description', TextType::class, array('required' => false));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'OF\CalendarBundle\Entity\ElementVisite'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'of_calendarbundle_elementvisite';
    }


}
