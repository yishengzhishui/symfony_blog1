<?php

namespace AppBundle\Security;

use AppBundle\Entity\Blog;
use AppBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class BlogVoter extends Voter
{
// these strings are just invented: you can use anything
    const DELETE = 'delete';
    const EDIT = 'edit';

    protected function supports($attribute, $subject)
    {
// if the attribute isn't one we support, return false
        if (!in_array($attribute, array(self::DELETE, self::EDIT))) {
            return false;
        }

// only vote on blog objects inside this voter
        if (!$subject instanceof blog) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute($attribute, $blog, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
// the user must be logged in; if not, deny access
            return false;
        }

        return $user === $blog->getUSer();

    }
}