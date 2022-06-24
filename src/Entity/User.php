<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    private $email;

    #[ORM\Column(type: 'json')]
    private $roles = [];

    #[ORM\Column(type: 'string')]
    private $password;

    #[ORM\Column(type: 'string', length: 50)]
    private $discord;

    #[ORM\Column(type: 'string', length: 255)]
    private $username;

    #[ORM\Column(type: 'string', length: 50, nullable: true)]
    private $Rango;

    #[ORM\ManyToOne(targetEntity: Grupo::class, inversedBy: 'miembros')]
    private $grupo;

    #[ORM\OneToOne(mappedBy: 'autor', targetEntity: Grupo::class, cascade: ['persist', 'remove'])]
    private $creador;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $Baneado;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $rangoValorant;

    #[ORM\OneToMany(mappedBy: 'Autor', targetEntity: Reporte::class)]
    private $reportes;

    #[ORM\OneToMany(mappedBy: 'Reportado', targetEntity: Reporte::class)]
    private $reportedIn;

    public function __construct()
    {
        $this->reportes = new ArrayCollection();
        $this->reportedIn = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getDiscord(): ?string
    {
        return $this->discord;
    }

    public function setDiscord(string $discord): self
    {
        $this->discord = $discord;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getRango(): ?string
    {
        return $this->Rango;
    }

    public function setRango(?string $Rango): self
    {
        $this->Rango = $Rango;

        return $this;
    }

    public function getGrupo(): ?Grupo
    {
        return $this->grupo;
    }

    public function setGrupo(?Grupo $grupo): self
    {
        $this->grupo = $grupo;

        return $this;
    }

    public function getCreador(): ?Grupo
    {
        return $this->creador;
    }

    public function setCreador(?Grupo $creador): self
    {
        // unset the owning side of the relation if necessary
        if ($creador === null && $this->creador !== null) {
            $this->creador->setAutor(null);
        }

        // set the owning side of the relation if necessary
        if ($creador !== null && $creador->getAutor() !== $this) {
            $creador->setAutor($this);
        }

        $this->creador = $creador;

        return $this;
    }

    public function isBaneado(): ?bool
    {
        return $this->Baneado;
    }

    public function setBaneado(?bool $Baneado): self
    {
        $this->Baneado = $Baneado;

        return $this;
    }

    public function getRangoValorant(): ?string
    {
        return $this->rangoValorant;
    }

    public function setRangoValorant(?string $rangoValorant): self
    {
        $this->rangoValorant = $rangoValorant;

        return $this;
    }

    /**
     * @return Collection<int, Reporte>
     */
    public function getReportes(): Collection
    {
        return $this->reportes;
    }

    public function addReporte(Reporte $reporte): self
    {
        if (!$this->reportes->contains($reporte)) {
            $this->reportes[] = $reporte;
            $reporte->setAutor($this);
        }

        return $this;
    }

    public function removeReporte(Reporte $reporte): self
    {
        if ($this->reportes->removeElement($reporte)) {
            // set the owning side to null (unless already changed)
            if ($reporte->getAutor() === $this) {
                $reporte->setAutor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Reporte>
     */
    public function getReportedIn(): Collection
    {
        return $this->reportedIn;
    }

    public function addReportedIn(Reporte $reportedIn): self
    {
        if (!$this->reportedIn->contains($reportedIn)) {
            $this->reportedIn[] = $reportedIn;
            $reportedIn->setReportado($this);
        }

        return $this;
    }

    public function removeReportedIn(Reporte $reportedIn): self
    {
        if ($this->reportedIn->removeElement($reportedIn)) {
            // set the owning side to null (unless already changed)
            if ($reportedIn->getReportado() === $this) {
                $reportedIn->setReportado(null);
            }
        }

        return $this;
    }
}
