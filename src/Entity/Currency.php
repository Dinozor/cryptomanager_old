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
     * @var string
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=5, nullable=false)
     * @var string
     */
    private $code_a;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     * @var int
     */
    private $code_n;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     * @var int
     */
    private $minor_unit;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @var string
     */
    private $fraction_unit;

    /**
     * @ORM\Column(type="string", length=5, nullable=true)
     * @var string
     */
    private $symbol;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @var string
     */
    private $country;

    /**
     * @var boolean If currency is not enabled it should not be displayed or used anywhere
     * Like CurrencyPairs, balances etc. would be locked/disabled
     * @ORM\Column(type="boolean", nullable=false, options={"default":true})
     * @var bool
     */
    private $isActive;

    /**
     * @var boolean Shows if there was a problem with a currency/rate
     * Lock is set automatically if some problem persists (to old, no updates etc.)
     * Logs and comments should tell what is wrong
     *
     * New currencies are locked automatically in case they were created accidentally or imported via API
     *
     * @ORM\Column(type="boolean", nullable=false, options={"default":true})
     * @var bool
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
        $this->isActive = true;
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
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @param string $name
     * @return self
     */
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get the value of code_a
     *
     * @return string
     */
    public function getCode_a(): string
    {
        return $this->code_a;
    }

    /**
     * Set the value of code_a
     *
     * @param string $codeA
     * @return self
     */
    public function setCode_a(string $codeA): self
    {
        $this->code_a = $codeA;
        return $this;
    }

    /**
     * Get the value of code_n
     *
     * @return int
     */
    public function getCode_n(): int
    {
        return $this->code_n;
    }

    /**
     * Set the value of code_n
     *
     * @param int $codeN
     * @return self
     */
    public function setCode_n(int $codeN): self
    {
        $this->code_n = $codeN;
        return $this;
    }

    /**
     * Get the value of minor_unit
     *
     * @return int
     */
    public function getMinor_unit(): int
    {
        return $this->minor_unit;
    }

    /**
     * Set the value of minor_unit
     *
     * @param int $minorUnit
     * @return self
     */
    public function setMinor_unit(int $minorUnit): self
    {
        $this->minor_unit = $minorUnit;
        return $this;
    }

    /**
     * Get the value of fraction_unit
     *
     * @return string
     */
    public function getFraction_unit(): string
    {
        return $this->fraction_unit;
    }

    /**
     * Set the value of fraction_unit
     *
     * @param string $fractionUnit
     * @return self
     */
    public function setFraction_unit(string $fractionUnit): self
    {
        $this->fraction_unit = $fractionUnit;
        return $this;
    }

    /**
     * Get the value of symbol
     *
     * @return string
     */
    public function getSymbol(): string
    {
        return $this->symbol;
    }

    /**
     * Set the value of symbol
     *
     * @param string $symbol
     * @return self
     */
    public function setSymbol(string $symbol): self
    {
        $this->symbol = $symbol;
        return $this;
    }

    /**
     * Get the value of country
     *
     * @return string
     */
    public function getCountry(): string
    {
        return $this->country;
    }

    /**
     * Set the value of country
     *
     * @param string $country
     * @return self
     */
    public function setCountry(string $country): self
    {
        $this->country = $country;
        return $this;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->isActive && !$this->isLocked;
    }

    /**
     * @return bool
     */
    public function isNotActive(): bool
    {
        return !$this->isActive();
    }

    /**
     * @return bool
     */
    public function getIsActive(): bool
    {
        return $this->isActive && $this->isLocked;
    }

    /**
     * @param bool $isActive
     * @return Currency
     */
    public function setIsActive(bool $isActive): Currency
    {
        $this->isActive = $isActive;
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
     * @return Collection|Account[]
     */
    public function getAccounts(): Collection
    {
        return $this->accounts;
    }

    /**
     * @param Account $account
     * @return Currency
     */
    public function addAccount(Account $account): self
    {
        if (!$this->accounts->contains($account)) {
            $this->accounts[] = $account;
            $account->setCurrency($this);
        }

        return $this;
    }

    /**
     * @param Account $account
     * @return Currency
     */
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

    /**
     * @param CryptoNode $cryptoNode
     * @return Currency
     */
    public function addCryptoNode(CryptoNode $cryptoNode): self
    {
        if (!$this->cryptoNodes->contains($cryptoNode)) {
            $this->cryptoNodes[] = $cryptoNode;
            $cryptoNode->setCurrency($this);
        }

        return $this;
    }

    /**
     * @param CryptoNode $cryptoNode
     * @return Currency
     */
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

    /**
     * @param Currency $currency
     * @param float $float
     * @return int
     */
    public static function showMinorCurrency(Currency $currency, float $float): int
    {
        return (int)floor($float * (10 ** $currency->getMinor_unit()));
    }
}
