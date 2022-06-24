<?php

namespace App\Entity;

use App\Repository\ReporteRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReporteRepository::class)]
class Reporte
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'reportes')]
    private $Autor;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $Descripcion;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'reportedIn')]
    private $Reportado;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAutor(): ?User
    {
        return $this->Autor;
    }

    public function setAutor(?User $Autor): self
    {
        $this->Autor = $Autor;

        return $this;
    }

    public function getDescripcion(): ?string
    {
        return $this->Descripcion;
    }

    public function setDescripcion(?string $Descripcion): self
    {
        $this->Descripcion = $Descripcion;

        return $this;
    }

    public function getReportado(): ?User
    {
        return $this->Reportado;
    }

    public function setReportado(?User $Reportado): self
    {
        $this->Reportado = $Reportado;

        return $this;
    }
}
