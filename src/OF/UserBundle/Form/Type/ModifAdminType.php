<?php
// OFUserBundle/Form/Type/ProfileFormType.php
namespace OF\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
class ModifAdminType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // add your custom field
        $builder
        ->add('nom', TextType::class)
        ->add('prenom',TextType::class)
        ->add('username',TextType::class)
        ->add('email')
        ->add('save', SubmitType::class);
    }

  
}