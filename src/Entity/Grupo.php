<?php

namespace App\Entity;

use App\Repository\GrupoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GrupoRepository::class)]
class Grupo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $nombre;

    #[ORM\OneToMany(mappedBy: 'grupo', targetEntity: User::class)]
    private $miembros;

    #[ORM\OneToOne(inversedBy: 'creador', targetEntity: User::class, cascade: ['persist', 'remove'])]
    private $autor;

    public function __construct()
    {
        $this->miembros = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(?string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getMiembros(): Collection
    {
        return $this->miembros;
    }

    public function addMiembro(User $miembro): self
    {
        if (!$this->miembros->contains($miembro)) {
            $this->miembros[] = $miembro;
            $miembro->setGrupo($this);
        }

        return $this;
    }

    public function removeMiembro(User $miembro): self
    {
        if ($this->miembros->removeElement($miembro)) {
            // set the owning side to null (unless already changed)
            if ($miembro->getGrupo() === $this) {
                $miembro->setGrupo(null);
            }
        }

        return $this;
    }

    public function getAutor(): ?User
    {
        return $this->autor;
    }

    public function setAutor(?User $autor): self
    {
        $this->autor = $autor;

        return $this;
    }
}
