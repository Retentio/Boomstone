<?php

namespace Boomstone\Validator\Constraints;

use Boomgo\Provider\RepositoryProvider;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;

class BoomgoUniqueDocumentValidator extends ConstraintValidator
{
    private $repositoryProvider;
    private $documentNamespace;
    private $repositoryNamespace;

    public function __construct(RepositoryProvider $repositoryProvider)
    {
        $this->repositoryProvider = $repositoryProvider;
    }

    public function isValid($value, Constraint $constraint)
    {
        $repository = $this->repositoryProvider->get(get_class($value));
        $mapper = $repository->getMapper();
        $data = $mapper->serialize($value);

        $keys = $constraint->keys;
        $selector = array();
        foreach ($keys as $key) {
            if (!isset($data[$key])) {
                throw new ConstraintDefinitionException('Only key mapped by Boomgo can be validated for uniqueness');
            }

            $selector[$key] = $data[$key];
        }

        $occurrence = $repository->count($selector);

        if ($occurrence > 0) {
             $this->context->addViolation($constraint->message, array(
                '{{ value }}' => implode(',', $keys)));

            return false;
        }

        return true;
    }
}