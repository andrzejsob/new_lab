<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\Constraints\PostCode;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ClientRepository")
 */
class Client
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $street;

    /**
     * @ORM\Column(type="string", length=10)
     * @Assert\NotBlank
     */
    private $homeNumber;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $flatNumber;

    /**
     * @ORM\Column(type="string", length=10)
     * @Assert\NotBlank()
     * @PostCode
     */
    private $postCode;

    /**
     * @ORM\Column(type="string", length=30)
     * @Assert\NotBlank
     */
    private $locality;

    /**
     * @ORM\Column(type="boolean")
     * @Assert\NotNull()
     */
    private $fromUmcs;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ContactPerson", mappedBy="clientId")
     */
    private $contactPerson;

    public function __construct()
    {
        $this->contactPerson = new ArrayCollection();
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

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(string $street): self
    {
        $this->street = $street;

        return $this;
    }

    public function getHomeNumber(): ?string
    {
        return $this->homeNumber;
    }

    public function setHomeNumber(string $homeNumber): self
    {
        $this->homeNumber = $homeNumber;

        return $this;
    }

    public function getFlatNumber(): ?string
    {
        return $this->flatNumber;
    }

    public function setFlatNumber(?string $flatNumber): self
    {
        $this->flatNumber = $flatNumber;

        return $this;
    }

    public function getPostCode(): ?string
    {
        return $this->postCode;
    }

    public function setPostCode(string $postCode): self
    {
        $this->postCode = $postCode;

        return $this;
    }

    public function getLocality(): ?string
    {
        return $this->locality;
    }

    public function setLocality(string $locality): self
    {
        $this->locality = $locality;

        return $this;
    }

    public function getFromUmcs(): ?bool
    {
        return $this->fromUmcs;
    }

    public function setFromUmcs(bool $fromUmcs): self
    {
        $this->fromUmcs = $fromUmcs;

        return $this;
    }

    public function getAddress(): ?string
    {
        return sprintf('%s %s, %s %s', [
            $this->getStreet(),
            implode('/', [$this->getHomeNumber(), $this->getFlatNumber()]),
            $this->getPostCode(),
            $this->getLocality()
        ]);
    }

    /**
     * @return Collection|ContactPerson[]
     */
    public function getContactPerson(): Collection
    {
        return $this->contactPerson;
    }

    public function addContactPerson(ContactPerson $contactPerson): self
    {
        if (!$this->contactPerson->contains($contactPerson)) {
            $this->contactPerson[] = $contactPerson;
            $contactPerson->setClient($this);
        }

        return $this;
    }

    public function removeContactPerson(ContactPerson $contactPerson): self
    {
        if ($this->contactPerson->contains($contactPerson)) {
            $this->contactPerson->removeElement($contactPerson);
            // set the owning side to null (unless already changed)
            if ($contactPerson->getClient() === $this) {
                $contactPerson->setClient(null);
            }
        }

        return $this;
    }
}
