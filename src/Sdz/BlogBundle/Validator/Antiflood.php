<?php
/**
 * Created by PhpStorm.
 * User: ben
 * Date: 05/08/14
 * Time: 13:34
 */

namespace Sdz\BlogBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * Class Antiflood
 * @package Sdz\BlogBundle\Validator
 *
 * @Annotation
 */
class Antiflood  extends Constraint
{
    public $message = "Vous ne pouvez poster un message que toutes les 15 minutes, %string% donnée";

    public function validatedBy()
    {
        return "sdzblog_antiflood";
    }
} 