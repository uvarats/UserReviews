<?php

declare(strict_types=1);

namespace App\Validator;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedValueException;
use Symfony\Contracts\Translation\TranslatorInterface;

class IsNameOccupiedValidator extends ConstraintValidator
{
    private EntityManagerInterface $em;
    private TranslatorInterface $translator;

    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em, TranslatorInterface $translator)
    {
        $this->em = $em;
        $this->translator = $translator;
    }

    public function validate($value, Constraint $constraint)
    {
        /* @var IsNameOccupied $constraint */
        if(!$constraint instanceof IsNameOccupied){
            throw new UnexpectedValueException($constraint, IsNameOccupied::class);
        }

        if (null === $value || '' === $value) {
            return;
        }
        if(!is_string($value)){
            throw new UnexpectedValueException($value, 'string');
        }
        $repo = $this->em->getRepository(User::class)->findAll();
        /** @var User $user */
        foreach ($repo as $user){
            if(strcasecmp($user->getUserIdentifier(), $value) === 0){
                $this->context->buildViolation($this->translator->trans($constraint->message))
//                    ->setParameter('{{ value }}', $value)
                    ->addViolation();
                break;
            }
        }
    }
}
