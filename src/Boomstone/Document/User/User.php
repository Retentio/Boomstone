<?php

namespace Boomstone\Document\User;

use Boomstone\Document\User\PasswordRequest;

/**
 * User
 *
 * @author Antoine Guiral
 * @author Ludovic Fleury <ludo.fleury@gmail.com>
 */
class User
{
    /**
     * @Boomgo native \MongoId
     * @var MongoId
     */
    private $id;

    /**
     * @Boomgo
     * @var string
     */
    private $email;

    /**
     * @Boomgo
     * @var string
     */
    private $password;

    /**
     * @Boomgo
     * @var string
     */
    private $salt;

    /**
     * @Boomgo
     * @var boolean
     */
    private $confirmed;

    /**
     * @Boomgo
     * @var boolean
     */
    private $locked;

    /**
     * @Boomgo
     * @var array
     */
    private $roles;

    /**
     * @Boomgo Document Boomstone\Document\User\PasswordRequest
     */
    private $passwordRequest;

    /**
     * @Boomgo
     * @var timestamp
     */
    private $createdAt;

    public function __construct()
    {
        $this->enabled = false;
        $this->locked = false;
        $this->salt = \Boomstone\Utils\Toolbox::generateToken();
        $this->roles = array('ROLE_MEMBER');
    }

    public function __toString()
    {
        return $this->email;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getSalt()
    {
        return $this->salt;
    }

    public function setSalt($salt)
    {
        $this->salt = $salt;
    }

    public function getConfirmed()
    {
        return $this->confirmed;
    }

    public function setConfirmed($confirmed)
    {
        $this->confirmed = $confirmed;
    }

    public function isConfirmed($confirmed)
    {
        return $this->$confirmed;
    }

    public function getLocked()
    {
        return $this->locked;
    }

    public function setLocked($locked)
    {
        $this->locked = $locked;
    }

    public function isLocked()
    {
        return $this->locked();
    }

    public function getPasswordRequest()
    {
        return $this->passwordRequest;
    }

    public function setPasswordRequest(PasswordRequest $passwordRequest)
    {
        $this->passwordRequest = $passwordRequest;
    }

    /**
     * Reset a password request
     *
     * @param  string $password Optional new encoded password
     */
    public function resetPasswordRequest($password = null)
    {
        if (null !== $password) {
            $this->password = $password;
        }
        $this->passwordRequest = null;
    }

    public function getRoles()
    {
        return $this->roles;
    }

    public function setRoles(array $roles)
    {
        $this->roles = $roles;
    }

    public function hasRole($role)
    {
        return in_array($role, $this->roles);
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setCreatedAt($time)
    {
        $this->createdAt = $time;
    }
}