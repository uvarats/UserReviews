<?php

declare(strict_types=1);

namespace App\Validator;

use Symfony\Component\Translation\TranslatableMessage;
use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class IsNameOccupied extends Constraint
{
    /*
     * Any public properties become valid options for the annotation.
     * Then, use these in your validator class.
     */
    public $message = 'This username is occupied.';
}
