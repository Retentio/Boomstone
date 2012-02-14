<?php

/**
 * This file is part of the Boomstone PHP Silex boilerplate.
 *
 * https://github.com/Retentio/Boomstone
 *
 * (c) Ludovic Fleury <ludo.fleury@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Boomstone\Form;

use Symfony\Component\Form\AbstractType,
    Symfony\Component\Form\FormBuilder;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * PasswordRequestType
 *
 * @author Ludovic Fleury <ludo.fleury@gmail.com>
 */
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