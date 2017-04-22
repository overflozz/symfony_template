<?php
// OFUserBundle/Form/Type/ProfileFormType.php
namespace OF\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ProfileFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // add your custom field
        $builder->add('profilePictureFile');
    }

    public function getParent()
    {

        return 'FOS\UserBundle\Form\Type\ProfileFormType';
        
    }

  
}