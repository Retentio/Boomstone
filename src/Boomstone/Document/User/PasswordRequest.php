<?php

namespace Boomstone\Document\User;

/**
 * PasswordRequest
 *
 * @author Ludovic Fleury <ludo.fleury@gmail.com>
 */
class PasswordRequest
{
    /**
     * @var integer Token TTL in second
     */
    static $ttl = 3600;

    /**
     * @Boomgo
     * @var string
     */
    private $token;

    /**
     * @Boomgo
     * @var string
     */
    private $expiresAt;

    public function __construct()
    {
        $this->token = \Boomstone\Utils\Toolbox::generateToken();
        $this->expiresAt = time() + self::$ttl;
    }

    public function getToken()
    {
        return $this->token;
    }

    public function setToken($token)
    {
        $this->token = $token;
    }

    public function getExpiresAt()
    {
        return $this->expiresAt;
    }

    public function setExpiresAt($expiresAt)
    {
        $this->expiresAt = $expiresAt;
    }

    public function isExpired($date = null)
    {
        if (null === $date) {
            $date = time();
        }
        return $this->expiresAt < $date;
    }
}