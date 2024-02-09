<?php

namespace App\Validator\UniqueEmail;

use App\Utils\Firebase;
use Kreait\Firebase\Exception\Auth\UserNotFound;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class UniqueEmailValidator extends ConstraintValidator
{

    public function __construct(private Firebase $firebase)
    {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof UniqueEmail) {
            throw new UnexpectedTypeException($constraint, UniqueEmail::class);
        }
        try {
            $this->firebase->getFactory()->createAuth()->getUserByEmail($value);
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ string }}', $value)
                ->setCode('ALREADY_EXISTS')
                ->addViolation();
        } catch (UserNotFound $e) {
            // email is ok
        }

    }

}
