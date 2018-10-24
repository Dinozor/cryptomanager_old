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
     * @var string
     */
    private $hash;

    /**
     * @ORM\Column(type="string", length=255)
     * @var string
     */
    private $txid;

    /**
     * @ORM\Column(type="string", length=255)
     * @var string
     */
    private $fromAddress;

    /**
     * @ORM\Column(type="string", length=255)
     * @var string
     */
    private $toAddress;

    /**
     * @ORM\Column(type="string", length=255)
     * @var string
     */
    private $block;

    /**
     * @ORM\Column(type="integer", options={"default":0})
     * @var int
     */
    private $confirmations;

    /**
     * @ORM\Column(type="datetimetz")
     * @var \DateTimeInterface
     */
    private $timeCreated;

    /**
     * @ORM\Column(type="bigint")
     * @var int
     */
    private $amount;

    /**
     * @ORM\Column(type="string", length=255)
     * @var string
     */
    private $status;

    /**
     * @ORM\Column(type="json", nullable=true)
     * @var array
     */
    private $extra = [];

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Currency", inversedBy="transactions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $currency;

    /**
     * @ORM\Column(type="datetimetz")
     * @var \DateTimeInterface
     */
    private $timeUpdated;

    /**
     * Transaction constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $this->timeCreated = new \DateTimeImmutable();
    }

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

    /**
     * @return string
     */
    public function getTxid(): string
    {
        return $this->txid;
    }

    public function setTxid(string $txid): self
    {
        $this->txid = $txid;
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

    /**
     * @return int
     */
    public function getConfirmations(): int
    {
        return $this->confirmations;
    }

    public function setConfirmations(int $confirmations): self
    {
        $this->confirmations = $confirmations;
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

    public function getExtra(): ?array
    {
        return $this->extra;
    }

    public function setExtra(?array $extra): self
    {
        $this->extra = $extra;

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

    public function getTimeUpdated(): ?\DateTimeInterface
    {
        return $this->timeUpdated;
    }

    public function setTimeUpdated(\DateTimeInterface $timeUpdated): self
    {
        $this->timeUpdated = $timeUpdated;

        return $this;
    }
}
