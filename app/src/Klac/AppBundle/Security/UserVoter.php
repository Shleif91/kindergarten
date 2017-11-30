<?php

namespace Klac\AppBundle\Security;

use Klac\AppBundle\Entity\User;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class UserVoter extends Voter
{
    const VIEW = 'view';
    const EDIT = 'edit';
    const REMOVE = 'remove';

    protected function supports($attribute, $subject)
    {
        if (!in_array($attribute, array(self::VIEW, self::EDIT, self::REMOVE))) {
            return false;
        }

        if (!$subject instanceof User) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            # the user must be logged in; if not, deny access
            return false;
        }

        switch ($attribute) {
            case self::VIEW:
                return $this->canView($subject, $user);
            case self::EDIT:
                return $this->canEdit($subject, $user);
            case self::REMOVE:
                return $this->canRemove($subject, $user);
        }

        throw new \LogicException('This code should not be reached!');
    }

    private function canView(User $subject, User $user)
    {
        if ($subject->getId() === $user->getId()) {
            return true;
        }

        return false;
    }

    private function canEdit(User $subject, User $user)
    {
        if ($subject->getId() === $user->getId()) {
            return true;
        }

        return false;
    }

    private function canRemove(User $subject, User $user)
    {
        if ($subject->getId() === $user->getId()) {
            return true;
        }

        return false;
    }
}