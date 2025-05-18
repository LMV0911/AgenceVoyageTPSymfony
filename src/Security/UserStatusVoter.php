<?php

namespace App\Security;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class UserStatusVoter extends Voter
{
  protected function supports(string $attribute, $subject): bool
  {
    return $attribute === 'USER_NOT_BANNED';
  }

  protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
  {
    $user = $token->getUser();

    if (!$user instanceof User) {
      return false;
    }

    return !in_array('ROLE_BANNED', $user->getRoles());
  }
}
