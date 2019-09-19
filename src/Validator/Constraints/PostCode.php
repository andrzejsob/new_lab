<?php


namespace App\Validator\Constraints;


use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class PostCode extends Constraint
{
    public $message = 'inappropriate_post_code';
}