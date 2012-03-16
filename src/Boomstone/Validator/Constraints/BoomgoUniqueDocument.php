<?php

namespace Boomstone\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class BoomgoUniqueDocument extends Constraint
{
    public $message = 'The value(s) {{ value }} is(are) already used';
    public $keys = array();

    public function getRequiredOptions()
    {
        return array('keys');
    }

    public function getDefaultOption()
    {
        return 'keys';
    }

    public function validatedBy()
    {
        return 'boomgo.validator.unique';
    }

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}