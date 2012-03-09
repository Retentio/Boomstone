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

namespace Boomstone\Repository\User;

use Boomgo\Repository;
use Boomstone\Document\User\User,
    Boomstone\Document\User\PasswordRequest;

/**
 * UserRepository
 *
 * @author Antoine Guiral
 * @author Ludovic Fleury <ludo.fleury@gmail.com>
 */
class UserRepository extends Repository
{
    const COLLECTION = 'users';

    /**
     * Return an User or false
     *
     * @param  string $email
     * @return mixed  User|null
     */
    public function findOneByEmail($email)
    {
        $data = $this->connection
            ->selectDB('boomstone')
            ->selectCollection(self::COLLECTION)
            ->findOne(array('email' => $email));

        if (null === $data)  {
            return null;
        }

        return $this->getMapper()->unserialize($data);
    }

    /**
     * Return an User from a valid & active recovery token
     *
     * @param  string $token
     * @return mixed  User|null
     */
    public function findOneByRecoveryToken($token)
    {
        $data = $this->connection
            ->selectDB('retentio')
            ->selectCollection($this->getCollectionName())
            ->findOne(array('$and' => array(
                array('password_request.token' => $token),
                array('password_request.expires_at' => array('$gte' => time())))));

        if (null === $data)  {
            return null;
        }
        return $this->getMapper()->hydrate($data);
    }

    /**
     * Authenticate an user
     *
     * @param  User   $user
     * @param  string $password
     * @return mixed  boolean
     */
    public function authenticate(User $user, $password)
    {
        $encodedPassword = \Boomstone\Utils\Toolbox::encode($password, $user->getSalt());

        if ($user->getPassword() === $encodedPassword) {
            return true;
        }

        return false;
    }

    public function save(User $user, array $options = array())
    {
        $this->connection
            ->selectDB('boomstone')
            ->selectCollection(self::COLLECTION)
            ->save($this->getMapper()->serialize($user), $options);
    }
}