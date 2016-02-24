<?php

namespace AppBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface;

use Gedmo\Mapping\Annotation as Gedmo;

use Doctrine\ORM\Mapping as ORM;

use DateTime;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 */
class User implements UserInterface, \Serializable
{
    const ROLE_USER = 'ROLE_USER';
    const ROLE_ADMIN = 'ROLE_ADMIN';

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, unique=true)
     */
    protected $username;

    /**
     * @var string
     */
    protected $plainPassword;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    protected $password;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    protected $salt;

    /**
     * @var array
     *
     * @ORM\Column(type="array")
     */
    protected $roles = array();

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    protected $firstName;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    protected $lastName;

    /**
     * @Gedmo\Slug(fields={"firstName", "lastName"})
     *
     * @ORM\Column(length=128, unique=true)
     */
    protected $slug;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true, unique=true)
     */
    protected $resetToken;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $passwordRequestedAt;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $lastLogin = null;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $createdAt;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->salt         = md5(uniqid(null, true));
        $this->roles        = array(self::ROLE_USER);
        $this->createdAt    = new DateTime();
    }

    /**
     * @param  array $roles
     * @return User
     */
    public function setRoles(array $roles)
    {
        $this->roles = array();

        foreach ($roles as $role) {
            $this->addRole($role);
        }

        return $this;
    }

    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @param  string $role
     * @return User
     */
    public function addRole($role)
    {
        $role = strtoupper($role);
        if (!in_array($role, $this->roles, true)) {
            $this->roles[] = $role;
        }
        return $this;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        $this->plainPassword = null;
    }

    public function getEmail()
    {
        return $this->username;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setLastLogin($lastLogin)
    {
        $this->lastLogin = $lastLogin;
    }

    public function getLastLogin()
    {
        return $this->lastLogin;
    }

    public function setPlainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;
    }

    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    public function getFirstName()
    {
        return $this->firstName;
    }

    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    public function getLastName()
    {
        return $this->lastName;
    }

    public function getFullName()
    {
        return $this->firstName . ' ' . $this->lastName;
    }

    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param \DateTime $passwordRequestedAt
     */
    public function setPasswordRequestedAt($passwordRequestedAt)
    {
        $this->passwordRequestedAt = $passwordRequestedAt;
    }

    /**
     * @return \DateTime
     */
    public function getPasswordRequestedAt()
    {
        return $this->passwordRequestedAt;
    }

    /**
     * @param string $resetToken
     */
    public function setResetToken($resetToken)
    {
        $this->resetToken = $resetToken;
    }

    /**
     * @return string
     */
    public function getResetToken()
    {
        return $this->resetToken;
    }

    public function clearResetToken()
    {
        $this->resetToken = null;
        $this->passwordRequestedAt = null;
    }

    // Password reset requests are valid for a day
    public function isPasswordRequestNonExpired()
    {
        return $this->getPasswordRequestedAt() instanceof \DateTime &&
        $this->getPasswordRequestedAt()->getTimestamp() + 86400 > time();
    }

    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
            $this->salt,
        ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
            $this->salt
        ) = unserialize($serialized);
    }

}

