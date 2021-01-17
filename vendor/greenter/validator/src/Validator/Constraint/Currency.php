<?php
/**
 * Created by PhpStorm.
 * User: Administrador
 * Date: 25/01/2018
 * Time: 02:19 PM
 */

namespace Greenter\Validator\Constraint;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class Currency extends Constraint
{
    public $message = 'The value {{ value }} is not a valid currency.';
}