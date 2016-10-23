<?php
/**
 * Created by PhpStorm.
 * User: carlosagudobelloso
 * Date: 23/10/16
 * Time: 14:24
 */

namespace AppBundle\Validator\Constraints;


use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ValidImage extends Constraint
{
    public $message = 'This is not a valid image';

    public function validatedBy()
    {
        return get_class($this).'Validator';
    }

}