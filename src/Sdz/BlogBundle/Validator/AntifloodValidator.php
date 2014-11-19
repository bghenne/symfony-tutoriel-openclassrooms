<?php
/**
 * Created by PhpStorm.
 * User: ben
 * Date: 05/08/14
 * Time: 15:58
 */

namespace Sdz\BlogBundle\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;

class AntifloodValidator extends ConstraintValidator
{
    private $request;
    private $em;

    public function __construct(Request $request, EntityManager $em)
    {
        $this->request = $request;
        $this->em = $em;
    }

    public function validate($value, Constraint $constraint)
    {
        $ip = $this->request->server->get('REMOTE_ADDR');

//        $isFlood = $this->em->getRepository('SdzBlogBundle:Commentaire')
//            ->isFlood($ip, 4);
//
//        if (strlen($value) < 3 && $isFlood) {
//            $this->context->buildViolation($constraint->message, array('%string%' => $value))
//                 ->addViolation();
//        }
    }
} 