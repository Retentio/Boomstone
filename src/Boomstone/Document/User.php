<?php

/**
 * This file is part of the Boomstone PHP Silex boilerplate.
 *
 * https://github.com/Retentio/Boomstone
 *
 * (c) Ludovic Fleury <ludo.fleury@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Boomstone\Document;

use Boomstone\Document\PasswordRequest;

/**
 * User
 *
 * @author Antoine Guiral
 * @author Ludovic Fleury <ludo.fleury@gmail.com>
 */
class User
{
    /**
     * @Persistent
     * @var \MongoId
     */
    private $id;

    /**
     * @Persistent
     * @var string
     */
    private $email;

    /**
     * @Persistent
     * @var string
     */
    private $password;

    /**
     * @Persistent
     * @var string
     */
    private $salt;

    /**
     * @Persistent
     * @var boolean
     */
    private $confirmed;

    /**
     * @Persistent
     * @var boolean
     */
    private $locked;

    /**
     * @Persistent
     * @var array
     */
    private $roles;

    /**
     * @Persistent
     * @var Boomstone\Document\PasswordRequest
     */
    private $passwordRequest;

    /**
     * @Persistent
     * @var string
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