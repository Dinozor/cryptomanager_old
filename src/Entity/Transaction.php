<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TransactionRepository")
 */
class Transaction
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $hash;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $fromAddress;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $toAddress;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $block;

    /**
     * @ORM\Column(type="datetimetz")
     */
    private $timeCreated;

    /**
     * @ORM\Column(type="bigint")
     */
    private $amount;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHash(): ?string
    {
        return $this->hash;
    }

    public function setHash(string $hash): self
    {
        $this->hash = $hash;

        return $this;
    }

    public function getFromAddress(): ?string
    {
        return $this->fromAddress;
    }

    public function setFromAddress(string $fromAddress): self
    {
        $this->fromAddress = $fromAddress;

        return $this;
    }

    public function getToAddress(): ?string
    {
        return $this->toAddress;
    }

    public function setToAddress(string $toAddress): self
    {
        $this->toAddress = $toAddress;

        return $this;
    }

    public function getBlock(): ?string
    {
        return $this->block;
    }

    public function setBlock(string $block): self
    {
        $this->block = $block;

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

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }
}
