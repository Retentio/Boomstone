<?php

namespace Boomstone\Form;

use Symfony\Component\Form\AbstractType,
    Symfony\Component\Form\FormBuilder;

use Symfony\Component\Validator\Constraints as Assert;

class UserType extends AbstractType
{
    public function getName()
    {
        return 'signin';
    }

    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('email', 'email', array('required' => true));
        $builder->add('password', 'password', array('required' => true));
    }

    public function getDefaultOptions(array $options)
    {
        $options = array_merge(array(
            'validation_constraint' => new Assert\Collection(array(
                'fields' => array(
                    'email' => array(new Assert\NotBlank(), new Assert\Email()),
                    'password' => new Assert\NotBlank(),
                ),
                'allowExtraFields' => true,
            ))
        ), $options);

        return $options;
    }
}