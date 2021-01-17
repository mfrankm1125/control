<?php
/**
 * Created by PhpStorm.
 * User: Administrador
 * Date: 25/01/2018
 * Time: 02:24 PM
 */

namespace Greenter\Validator\Constraint;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class CodeUnit extends Constraint
{
    public $message = 'The value {{ value }} is not a valid unit code.';
}