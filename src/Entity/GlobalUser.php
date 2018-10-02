<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GlobalUserRepository")
 */
class GlobalUser
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string $guid
     * @ORM\Column(type="guid", nullable=false, unique=true)
     */
    private $guid;

    /**
     * @var Key[] $keys
     * @ORM\OneToMany(targetEntity="App\Entity\Key", mappedBy="user")
     */
    private $keys;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Account", mappedBy="globalUser")
     */
    private $accounts;

    public function __construct()
    {
        $this->accounts = new ArrayCollection();
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
     * @return GlobalUser
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getGuid(): string
    {
        return $this->guid;
    }

    /**
     * @param string $guid
     * @return GlobalUser
     */
    public function setGuid(string $guid): GlobalUser
    {
        $this->guid = $guid;
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
            $account->setGlobalUser($this);
        }

        return $this;
    }

    public function removeAccount(Account $account): self
    {
        if ($this->accounts->contains($account)) {
            $this->accounts->removeElement($account);
            // set the owning side to null (unless already changed)
            if ($account->getGlobalUser() === $this) {
                $account->setGlobalUser(null);
            }
        }

        return $this;
    }
}
