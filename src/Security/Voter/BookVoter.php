<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Vote;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Entity\Book;

final class BookVoter extends Voter
{
    public const EDIT = 'BOOK_EDIT';
    public const VIEW = 'BOOK_VIEW';
	public const CREATE= 'BOOK_CREATE';

    protected function supports(string $attribute, mixed $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::EDIT, self::VIEW, self::CREATE])
            && $subject instanceof \App\Entity\Book;
    }
	
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token, ?Vote $vote = null): bool
    {
        $user = $token->getUser();

        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            $vote?->addReason('The user must be logged in to access this resource.');

            return false;
        }
		/** @var Book 
		* $book 
		* */ 
		$book = $subject;

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::EDIT:
                return in_array('ROLE_ADMIN', $user->getRoles(), true);

            case self::VIEW:
                return true;

            case self::CREATE:
                return in_array('ROLE_ADMIN', $user->getRoles(), true);
        }

        return false;
    }
}
