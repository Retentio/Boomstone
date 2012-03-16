<?php

namespace Boomstone\Validator;

use Silex\Application;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidatorFactoryInterface;

/**
 * Uses a service container to create constraint validators.
 *
 * @author Ludovic Fleury <ludo.fleury@gmail.com>
 */
class ConstraintValidatorFactory implements ConstraintValidatorFactoryInterface
{
    protected $application;
    protected $validators;

    /**
     * Constructor.
     *
     * @param SilexApplication $container  The service container
     * @param array            $validators An array of validators
     */
    public function __construct(Application $application, array $validators = array())
    {
        $this->application = $application;
        $this->validators = $validators;
    }

    /**
     * Returns the validator for the supplied constraint.
     *
     * @param Constraint $constraint A constraint
     *
     * @return Symfony\Component\Validator\ConstraintValidator A validator for the supplied constraint
     */
    public function getInstance(Constraint $constraint)
    {
        $name = $constraint->validatedBy();

        if (!isset($this->validators[$name])) {
            $this->validators[$name] = new $name();
        } elseif (is_string($this->validators[$name])) {
            $this->validators[$name] = $this->application[$this->validators[$name]];
        }

        return $this->validators[$name];
    }
}
