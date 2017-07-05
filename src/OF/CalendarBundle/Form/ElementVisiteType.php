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
        'Iroise' => 'Iroise',
        'Iroise, Espace de restauration et espace de créativité' => 'Iroise, Espace de restauration et espace de créativité',
        'Azur, centre de conférences' => 'Azur, centre de conférences',
        'Rez-de-chaussée Opale, espace exposition' =>'Rez-de-chaussée Opale, espace exposition',
        'Halle d’essais' => 'Halle d’essais',
        'Bancs d’essais AMA et THEMIS'=>'Bancs d’essais AMA et THEMIS',
        'Restauration'=>'Restauration',


        ), 'attr'  => array('class' => 'titreInput', 'placeholder' => 'Activité'))) // les classes pour le js
        ->add('duree', TimeType::class,array('attr'  => array('class' => 'dureeInput' , 'placeholder' => 'Durée')))
        ->add('description', TextType::class);
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
