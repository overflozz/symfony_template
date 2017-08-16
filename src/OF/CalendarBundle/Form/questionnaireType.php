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
        'Oui' =>0,
        'Moyennement' => 2,
        'Pas du tout' => 1),
        'expanded' => True,
        'multiple' => False,
        'label' => 'Cette visite vous a-t-elle permis de mieux connaitre les activités d’EDF R&D, et de changer votre vision d’EDF ? '))
        ->add('resultat2', ChoiceType::class,array('choices'  => array(
        'Non' => 1,
        'Partiellement' => 2,
        'Oui' => 0),
        'expanded' => True,
        'multiple' => False,
        'label' => 'Pensiez-vous que la R&D travaillait sur toutes ces thématiques ?'))
        ->add('resultat3', ChoiceType::class,array('choices'  => array(
        'Satisfaisant' => 3,
        'Assez satisfaisant' => 4,
        'Assez insatisfaisant' => 5,
        'Insatisfaisant' => 6),
        'expanded' => True,
        'multiple' => False,
        'label' => 'Quel était le but de la visite ?'))
        ->add('resultat4', ChoiceType::class,array('choices'  => array(
        'Oui' => 0,
        'Moyennement' => 2,
        'Pas du tout' => 1),
        'expanded' => True,
        'multiple' => False,
        'label' => ' Le parcours de visite proposé correspondait-il à ce que vous souhaitiez visiter ? '))
        ->add('resultat5', ChoiceType::class,array('choices'  => array(
        'Satisfaisants' => 3,
        'Assez satisfaisants' => 4,
        'Assez insatisfaisants' => 5,
        'Insatisfaisants' => 6),
        'expanded' => True,
        'multiple' => False,
        'label' => 'Comment avez-vous trouvé l’accompagnement et la préparation pour cette visite ?'))
        ->add('resultat6', ChoiceType::class,array('choices'  => array(
        'Oui' => 0,
        'Moyennement' => 2,
        'Pas du tout' => 1),
        'expanded' => True,
        'multiple' => False,
        'label' => 'Avez-vous été suffisamment informé en amont de la visite (contenu, parcours, accessibilité…) ?'))
        ->add('resultat7', ChoiceType::class,array('choices'  => array(
        'Satisfaisant' => 3,
        'Assez satisfaisante' => 4,
        'Assez insatisfaisante' => 5,
        'Insatisfaisante' => 6),
        'expanded' => True,
        'multiple' => False,
        'label' => 'Comment jugez-vous l’attitude de votre conférencier lors de cette visite ? '))
        ->add('resultat8', ChoiceType::class,array('choices'  => array(
       	'Oui' => 0,
        'Moyennement' => 2,
        'Pas du tout' => 1),
        'expanded' => True,
        'multiple' => False,
        'label' => 'Le niveau de discours et de technicité du conférencier vous a-t-il convenu ?'))
        ->add('resultat9', ChoiceType::class,array('choices'  => array(
        'Oui' => 0,
        'Moyennement' => 2,
        'Pas du tout' => 1),
        'expanded' => True,
        'multiple' => False,
        'label' => 'A-t-il répondu à toutes vos questions ? '));
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
