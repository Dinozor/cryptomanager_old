<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CryptoNodeRepository")
 */
class CryptoNode
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
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $className;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $mainAddress;

    /**
     * @ORM\Column(type="json_array", nullable=true)
     */
    private $settings;

    /**
     * @ORM\Column(type="datetimetz")
     */
    private $timeCreated;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Currency", inversedBy="cryptoNodes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $currency;


    /**
     * @ORM\Column(type="boolean")
     */
    private $isLocked;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isEnabled;

    /**
     * @ORM\Column(type="datetimetz")
     */
    private $timeUpdated;

    public function __construct()
    {
        $this->isEnabled = true;
        $this->isLocked = false;

        $dateTime = new \DateTimeImmutable();
        $this->timeCreated = $dateTime;
        $this->timeUpdated = $dateTime;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getClassName(): string
    {
        return $this->className;
    }

    public function setClassName(string $className): self
    {
        $this->className = $className;

        return $this;
    }

    public function getMainAddress(): ?string
    {
        return $this->mainAddress;
    }

    public function setMainAddress(string $mainAddress): self
    {
        $this->mainAddress = $mainAddress;

        return $this;
    }

    public function getSettings()
    {
        return $this->settings;
    }

    public function setSettings($settings): self
    {
        $this->settings = $settings;

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

    public function getCurrency(): ?Currency
    {
        return $this->currency;
    }

    public function setCurrency(?Currency $currency): self
    {
        $this->currency = $currency;

        return $this;
    }

    public function getIsActive(): bool
    {
        return $this->isEnabled && $this->isLocked;
    }

    public function getIsLocked(): bool
    {
        return $this->isLocked;
    }

    public function setIsLocked(bool $isLocked): self
    {
        $this->isLocked = $isLocked;

        return $this;
    }

    public function getIsEnabled(): ?bool
    {
        return $this->isEnabled;
    }

    public function setIsEnabled(bool $isEnabled): self
    {
        $this->isEnabled = $isEnabled;

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
