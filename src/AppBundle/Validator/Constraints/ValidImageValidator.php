<?php
/**
 * Created by PhpStorm.
 * User: carlosagudobelloso
 * Date: 23/10/16
 * Time: 14:27
 */

namespace AppBundle\Validator\Constraints;


use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ValidImageValidator extends ConstraintValidator
{

    /**
     * Checks if the passed value is valid.
     *
     * @param mixed $value The value that should be validated
     * @param Constraint $constraint The constraint for the validation
     */
    public function validate($value, Constraint $constraint)
    {
        // TODO: Implement validate() method.
    }
}