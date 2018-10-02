<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\WebserviceUserRepository")
 * @ORM\Table(name="user")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="string", unique=true)
     */
    private $apiKey;

    /**
     * @var array $roles
     * @ORM\Column(type="json")
     */
    private $roles;

    /**
     * @var bool $is_active
     * @ORM\Column(type="boolean")
     */
    private $is_active;
    /**
     * @var bool $is_locked
     * @ORM\Column(type="boolean")
     */
    private $is_locked;

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->is_active;
    }

    /**
     * @param bool $is_active
     * @return User
     */
    public function setIsActive(bool $is_active): User
    {
        $this->is_active = $is_active;
        return $this;
    }

    /**
     * @return bool
     */
    public function isLocked(): bool
    {
        return $this->is_locked;
    }

    /**
     * @param bool $is_locked
     * @return User
     */
    public function setIsLocked(bool $is_locked): User
    {
        $this->is_locked = $is_locked;
        return $this;
    }

    public function __construct()
    {
        $this->is_locked = false;
        $this->is_active = true;
    }

    /**
     * @return mixed
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * @param mixed $apiKey
     * @return User
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
        return $this;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getRoles()
    {
        return $this->roles;
//        return array('ROLE_USER', 'ROLE_DEVELOPER');
    }

    public function setRoles(array $roles)
    {
        $this->roles = $roles;
    }

    public function getPassword()
    {
    }

    public function getSalt()
    {
    }

    public function eraseCredentials()
    {
    }

    public function getID()
    {
        return $this->id;
    }

    public function validateAPIKey($apiKey)
    {
        if ($this->apiKey === $apiKey) {
            return true;
        }
        return false;
    }

    public function setUsername(string $username)
    {
        $this->username = $username;
    }
}
