<?php

namespace App\Entity;

use App\Repository\UserEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserEntityRepository::class)]
class UserEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: RequestEntity::class)]
    private Collection $requestEntities;

    public function __construct()
    {
        $this->requestEntities = new ArrayCollection();
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

    /**
     * @return Collection<int, RequestEntity>
     */
    public function getRequestEntities(): Collection
    {
        return $this->requestEntities;
    }

    public function addRequestEntity(RequestEntity $requestEntity): self
    {
        if (!$this->requestEntities->contains($requestEntity)) {
            $this->requestEntities->add($requestEntity);
            $requestEntity->setUser($this);
        }

        return $this;
    }

    public function removeRequestEntity(RequestEntity $requestEntity): self
    {
        if ($this->requestEntities->removeElement($requestEntity)) {
            // set the owning side to null (unless already changed)
            if ($requestEntity->getUser() === $this) {
                $requestEntity->setUser(null);
            }
        }

        return $this;
    }
}
