<?php

namespace AppBundle\Manager;

use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

use Doctrine\ORM\EntityManager;

use AppBundle\Entity\User;

class UserManager
{
    protected $encoder;

    public function __construct(
        EncoderFactoryInterface $encoder
    )
    {
        $this->encoder = $encoder;
    }

    public function generatePassword(User $user)
    {
        if ($user->getPlainPassword()) {
            $passwordEncoder = $this->encoder->getEncoder($user);
            $password = $passwordEncoder->encodePassword($user->getPlainPassword(), $user->getSalt());

            $user->setPassword($password);
        }

        return $user;
    }
}
