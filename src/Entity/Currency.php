<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CurrencyRepository")
 */
class Currency
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $name;
    /**
     * @ORM\Column(type="string", length=5, nullable=false)
     */
    private $code_a;
    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $code_n;
    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $minor_unit;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $fraction_unit;
    /**
     * @ORM\Column(type="string", length=5, nullable=true)
     */
    private $symbol;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $country;

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->isEnabled && !$this->isLocked;
    }

    public function isNotActive(): bool
    {
        return !$this->isActive();
    }

    public function getIsActive(): bool
    {
        return $this->isEnabled && $this->isLocked;
    }

    /**
     * @param bool $isEnabled
     * @return Currency
     */
    public function setIsEnabled(bool $isEnabled): Currency
    {
        $this->isEnabled = $isEnabled;
        return $this;
    }

    /**
     * @return bool
     */
    public function isLocked(): bool
    {
        return $this->isLocked;
    }

    /**
     * @param bool $isLocked
     * @return Currency
     */
    public function setIsLocked(bool $isLocked): Currency
    {
        $this->isLocked = $isLocked;
        return $this;
    }

    /**
     * @var boolean If currency is not enabled it should not be displayed or used anywhere
     * Like CurrencyPairs, balances etc. would be locked/disabled
     * @ORM\Column(type="boolean", nullable=false, options={"default":true})
     */
    private $isEnabled;

    /**
     * @var boolean Shows if there was a problem with a currency/rate
     * Lock is set automatically if some problem persists (to old, no updates etc.)
     * Logs and comments should tell what is wrong
     *
     * New currencies are locked automatically in case they were created accidentally or imported via API
     *
     * @ORM\Column(type="boolean", nullable=false, options={"default":true})
     */
    private $isLocked;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Account", mappedBy="currency", orphanRemoval=true)
     */
    private $accounts;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\CryptoNode", mappedBy="currency")
     */
    private $cryptoNodes;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Transaction", mappedBy="currency")
     */
    private $transactions;

    public function __construct()
    {
        $this->isLocked = true;
        $this->isEnabled = true;
        $this->accounts = new ArrayCollection();
        $this->cryptoNodes = new ArrayCollection();
        $this->transactions = new ArrayCollection();
    }

    /**
     * Get the value of id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the value of name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @return  self
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of code_a
     */
    public function getCode_a()
    {
        return $this->code_a;
    }

    /**
     * Set the value of code_a
     *
     * @return  self
     */
    public function setCode_a($code_a)
    {
        $this->code_a = $code_a;

        return $this;
    }

    /**
     * Get the value of code_n
     */
    public function getCode_n()
    {
        return $this->code_n;
    }

    /**
     * Set the value of code_n
     *
     * @return  self
     */
    public function setCode_n($code_n)
    {
        $this->code_n = $code_n;

        return $this;
    }

    /**
     * Get the value of minor_unit
     */
    public function getMinor_unit()
    {
        return $this->minor_unit;
    }

    /**
     * Set the value of minor_unit
     *
     * @return  self
     */
    public function setMinor_unit($minor_unit)
    {
        $this->minor_unit = $minor_unit;

        return $this;
    }

    /**
     * Get the value of fraction_unit
     */
    public function getFraction_unit()
    {
        return $this->fraction_unit;
    }

    /**
     * Set the value of fraction_unit
     *
     * @return  self
     */
    public function setFraction_unit($fraction_unit)
    {
        $this->fraction_unit = $fraction_unit;

        return $this;
    }

    /**
     * Get the value of symbol
     */
    public function getSymbol()
    {
        return $this->symbol;
    }

    /**
     * Set the value of symbol
     *
     * @return  self
     */
    public function setSymbol($symbol)
    {
        $this->symbol = $symbol;

        return $this;
    }

    /**
     * Get the value of country
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set the value of country
     *
     * @return  self
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * @return Collection|Account[]
     */
    public function getAccounts(): Collection
    {
        return $this->accounts;
    }

    public function addAccount(Account $account): self
    {
        if (!$this->accounts->contains($account)) {
            $this->accounts[] = $account;
            $account->setCurrency($this);
        }

        return $this;
    }

    public function removeAccount(Account $account): self
    {
        if ($this->accounts->contains($account)) {
            $this->accounts->removeElement($account);
            // set the owning side to null (unless already changed)
            if ($account->getCurrency() === $this) {
                $account->setCurrency(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|CryptoNode[]
     */
    public function getCryptoNodes(): Collection
    {
        return $this->cryptoNodes;
    }

    public function addCryptoNode(CryptoNode $cryptoNode): self
    {
        if (!$this->cryptoNodes->contains($cryptoNode)) {
            $this->cryptoNodes[] = $cryptoNode;
            $cryptoNode->setCurrency($this);
        }

        return $this;
    }

    public function removeCryptoNode(CryptoNode $cryptoNode): self
    {
        if ($this->cryptoNodes->contains($cryptoNode)) {
            $this->cryptoNodes->removeElement($cryptoNode);
            // set the owning side to null (unless already changed)
            if ($cryptoNode->getCurrency() === $this) {
                $cryptoNode->setCurrency(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Transaction[]
     */
    public function getTransactions(): Collection
    {
        return $this->transactions;
    }

    public function addTransaction(Transaction $transaction): self
    {
        if (!$this->transactions->contains($transaction)) {
            $this->transactions[] = $transaction;
            $transaction->setCurrency($this);
        }

        return $this;
    }

    public function removeTransaction(Transaction $transaction): self
    {
        if ($this->transactions->contains($transaction)) {
            $this->transactions->removeElement($transaction);
            // set the owning side to null (unless already changed)
            if ($transaction->getCurrency() === $this) {
                $transaction->setCurrency(null);
            }
        }

        return $this;
    }
}
