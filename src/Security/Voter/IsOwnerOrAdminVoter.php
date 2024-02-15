<?php

namespace App\Security\Voter;

use App\Entity\User\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class IsOwnerOrAdminVoter extends Voter
{
    public function __construct(private Security $security)
    {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        return $attribute == 'IS_OWNER_OR_ADMIN';
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (!$user instanceof UserInterface) {
            return self::ACCESS_DENIED;
        }
        if ($this->security->isGranted('ROLE_ADMIN')) {
            return self::ACCESS_GRANTED;
        }

        if ($subject instanceof User) {
            if ($subject->getUserIdentifier() === $user->getUserIdentifier()) {
                return self::ACCESS_GRANTED;
            }
        }

        return self::ACCESS_ABSTAIN;
    }
}
