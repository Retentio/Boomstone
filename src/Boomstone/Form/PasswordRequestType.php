<?php

namespace Boomstone\Form;

use Symfony\Component\Form\AbstractType,
    Symfony\Component\Form\FormBuilder;

use Symfony\Component\Validator\Constraints as Assert;

class PasswordRequestType extends AbstractType
{
    public function getName()
    {
        return 'password_request';
    }

    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('email', 'email', array('required' => true));
    }

    public function getDefaultOptions(array $options)
    {
        $options = array_merge(array(
            'validation_constraint' => new Assert\Collection(array(
                'fields' => array(
                    'email' => array(new Assert\NotBlank(), new Assert\Email()),
                ),
                'allowExtraFields' => true,
            ))
        ), $options);

        return $options;
    }
}