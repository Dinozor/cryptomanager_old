<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TODO: This table should not be a part of the system. Need to relocate it ASAP
 * TODO: Private key should be encrypted and/or password protected
 * TODO: Should contain integrity check, checksums, tests and validations (to ensure that keys are not and won't be damaged)
 * @ORM\Entity(repositoryClass="App\Repository\KeyRepository")
 * @ORM\Table(name="`key`")
 */
class Key
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var User $user
     * @ORM\ManyToOne(targetEntity="App\Entity\GlobalUser")
     * @ORM\JoinColumn(name="user_id", nullable=true)
     */
    private $user;

    /**
     * @ORM\Column(type="guid", nullable=true)
     */
    private $user_guid;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $private;

    /**
     * Type of key. Like EC, Hierarchical,
     * @ORM\Column(type="string")
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Currency")
     * @ORM\JoinColumn(name="currency_id", nullable=true)
     */
    private $currency;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=false, options={"default":"2018-01-01 00:00:00"})
     */
    private $time_created;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=false, options={"default":"2018-01-01 00:00:00"})
     */
    private $time_last_used;

    /**
     * Key constructor.
     */
    public function __construct()
    {
        $this->time_created = new \DateTimeImmutable();
        $this->time_last_used = new \DateTimeImmutable();
    }

    /**
     * @return mixed
     */
    public function getTimeCreated()
    {
        return $this->time_created;
    }

    /**
     * @param mixed $time_created
     * @return Key
     */
    public function setTimeCreated($time_created)
    {
        $this->time_created = $time_created;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTimeLastUsed()
    {
        return $this->time_last_used;
    }

    /**
     * @param mixed $time_last_used
     * @return Key
     */
    public function setTimeLastUsed($time_last_used)
    {
        $this->time_last_used = $time_last_used;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return Key
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return GlobalUser
     */
    public function getUser(): GlobalUser
    {
        return $this->user;
    }

    /**
     * @param Global`User $user
     * @return Key
     */
    public function setUser(GlobalUser $user): Key
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUserGuid()
    {
        return $this->user_guid;
    }

    /**
     * @param mixed $user_guid
     * @return Key
     */
    public function setUserGuid($user_guid)
    {
        $this->user_guid = $user_guid;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPrivate()
    {
        return $this->private;
    }

    /**
     * @param mixed $private
     * @return Key
     */
    public function setPrivate($private)
    {
        $this->private = $private;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     * @return Key
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param mixed $currency
     * @return Key
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
        return $this;
    }
}
