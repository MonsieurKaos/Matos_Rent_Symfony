<?php
namespace App\Entity;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Emprunt
{
    #[ORM\Column(name: 'id', type: 'integer')]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    private $id;
    #[Assert\NotBlank]
    #[ORM\Column(name: 'id_user', type: 'integer')]
    protected string $idUser;
    #[Assert\NotBlank]
    #[ORM\Column(name: 'id_materiel', type: 'integer')]
    protected string $idMateriel;
    #[ORM\Column(name: 'date_emprunt', type: 'datetime', nullable: true)]
    protected ?DateTimeInterface $dateEmprunt = null;
    #[ORM\Column(name: 'date_retour_previsionelle', type: 'datetime', nullable: true)]
    protected ?DateTimeInterface $dateRetourPrev = null;
    #[ORM\Column(name: 'date_retour', type: 'datetime', nullable: true)]
    protected ?DateTimeInterface $dateRetour = null;
    #[Assert\NotBlank]
    #[ORM\Column(name: 'statut', type: 'integer')]
    protected string $statut;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getIdUser(): int
    {
        return $this->idUser;
    }

    /**
     * @param int $idUser
     */
    public function setIdUser(int $idUser): void
    {
        $this->idUser = $idUser;
    }

    /**
     * @return int
     */
    public function getIdMateriel(): int
    {
        return $this->idMateriel;
    }

    /**
     * @param int $idMateriel
     */
    public function setIdMateriel(int $idMateriel): void
    {
        $this->idMateriel = $idMateriel;
    }

    /**
     * @return mixed
     */
    public function getDateEmprunt(): mixed
    {
        return $this->dateEmprunt;
    }

    public function setDateEmprunt(mixed $dateEmprunt): void
    {
        $this->dateEmprunt = $dateEmprunt;
    }

    /**
     * @return mixed
     */
    public function getDateRetourPrev(): mixed
    {
        return $this->dateRetourPrev;
    }

    /**
     * @param mixed $dateRetourPrev
     */
    public function setDateRetourPrev(mixed $dateRetourPrev): void
    {
        $this->dateRetourPrev = $dateRetourPrev;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getDateRetour(): ?DateTimeInterface
    {
        return $this->dateRetour;
    }

    /**
     * @param DateTimeInterface|null $dateRetour
     */
    public function setDateRetour(?DateTimeInterface $dateRetour): void
    {
        $this->dateRetour = $dateRetour;
    }

    /**
     * @return string
     */
    public function getStatut(): string
    {
        return $this->statut;
    }

    /**
     * @param string $statut
     */
    public function setStatut(string $statut): void
    {
        $this->statut = $statut;
    }
}