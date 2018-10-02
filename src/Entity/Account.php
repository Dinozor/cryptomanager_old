<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AccountRepository")
 */
class Account
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\GlobalUser", inversedBy="accounts")
     */
    private $globalUser;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $address;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Currency", inversedBy="accounts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $currency;

    /**
     * @ORM\Column(type="integer")
     */
    private $lastBlock;

    /**
     * @ORM\Column(type="bigint")
     */
    private $lastBalance;

    /**
     * @ORM\Column(type="datetimetz")
     */
    private $timeCreated;

    /**
     * @ORM\Column(type="datetimetz")
     */
    private $timeUpdated;

    /**
     * @ORM\Column(type="integer")
     */
    private $blockWhenCreated;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $password;

    public function __construct()
    {
        $dateTime = new \DateTimeImmutable();
        $this->timeCreated = $dateTime;
        $this->timeUpdated = $dateTime;
        $this->lastBalance = 0;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getGlobalUser(): ?GlobalUser
    {
        return $this->globalUser;
    }

    public function setGlobalUser(?GlobalUser $globalUser): self
    {
        $this->globalUser = $globalUser;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getCurrency(): ?Currency
    {
        return $this->currency;
    }

    public function setCurrency(?Currency $currency): self
    {
        $this->currency = $currency;

        return $this;
    }

    public function getLastBlock(): ?int
    {
        return $this->lastBlock;
    }

    public function setLastBlock(int $lastBlock): self
    {
        $this->lastBlock = $lastBlock;

        return $this;
    }

    public function getLastBalance(): ?int
    {
        return $this->lastBalance;
    }

    public function setLastBalance(int $lastBalance): self
    {
        $this->lastBalance = $lastBalance;

        return $this;
    }

    public function getTimeCreated(): ?\DateTimeInterface
    {
        return $this->timeCreated;
    }

    public function setTimeCreated(\DateTimeInterface $timeCreated): self
    {
        $this->timeCreated = $timeCreated;

        return $this;
    }

    public function getTimeUpdated(): ?\DateTimeInterface
    {
        return $this->timeUpdated;
    }

    public function setTimeUpdated(\DateTimeInterface $timeUpdated): self
    {
        $this->timeUpdated = $timeUpdated;

        return $this;
    }

    public function getBlockWhenCreated(): ?int
    {
        return $this->blockWhenCreated;
    }

    public function setBlockWhenCreated(int $blockWhenCreated): self
    {
        $this->blockWhenCreated = $blockWhenCreated;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }
}
