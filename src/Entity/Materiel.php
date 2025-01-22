<?php
namespace App\Entity;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Materiel
{
    #[ORM\Column(name: 'id', type: 'integer')]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    private $id;
    #[Assert\NotBlank]
    #[ORM\Column(name: 'nom', type: 'string', length: 35)]
    protected string $nom;
    #[Assert\NotBlank]
    #[ORM\Column(name: 'version', type: 'string', length: 15)]
    protected string $version;
    #[Assert\NotBlank]
    #[ORM\Column(name: 'ref', type: 'string', length: 7)]
    protected string $ref;
    #[ORM\Column(name: 'photo', type: 'string', length: 255, nullable: true)]
    protected ?string $photo=null;
    #[ORM\Column(name: 'telephone', type: 'string', length: 11, nullable: true)]
    private ?string $telephone = null;
    protected int $statut;
    protected float $longitude;
    protected float $latitude;
    protected string $emprunteur;

    protected ?DateTimeInterface $dateEmprunt = null;
    protected ?DateTimeInterface $dateRetourPrev = null;

    protected string $userEC;
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
     * @return string
     */
    public function getNom(): string
    {
        return $this->nom;
    }

    /**
     * @param string $nom
     */
    public function setNom(string $nom): void
    {
        $this->nom = $nom;
    }

    /**
     * @return string
     */
    public function getVersion(): string
    {
        return $this->version;
    }

    /**
     * @param string $version
     */
    public function setVersion(string $version): void
    {
        $this->version = $version;
    }

    /**
     * @return string
     */
    public function getRef(): string
    {
        return $this->ref;
    }

    /**
     * @param string $ref
     */
    public function setRef(string $ref): void
    {
        $this->ref = $ref;
    }

    /**
     * @return ?string
     */
    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    /**
     * @param string $photo
     */
    public function setPhoto(string $photo): void
    {
        $this->photo = $photo;
    }

    /**
     * @return ?string
     */
    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    /**
     * @param string $telephone
     */
    public function setTelephone(string $telephone): void
    {
        $this->telephone = $telephone;
    }

    /**
     * @return int
     */
    public function getStatut(): int
    {
        return $this->statut;
    }

    /**
     * @param int $statut
     */
    public function setStatut(int $statut): void
    {
        $this->statut = $statut;
    }

    /**
     * @return float
     */
    public function getLongitude(): float
    {
        return $this->longitude;
    }

    /**
     * @param float $longitude
     */
    public function setLongitude(float $longitude): void
    {
        $this->longitude = $longitude;
    }

    /**
     * @return float
     */
    public function getLatitude(): float
    {
        return $this->latitude;
    }

    /**
     * @param float $latitude
     */
    public function setLatitude(float $latitude): void
    {
        $this->latitude = $latitude;
    }

    /**
     * @return string
     */
    public function getUserEC(): string
    {
        return $this->userEC;
    }

    /**
     * @param string $userEC
     */
    public function setUserEC(string $userEC): void
    {
        $this->userEC = $userEC;
    }

    /**
     * @return mixed
     */
    public function getDateEmprunt(): mixed
    {
        return $this->dateEmprunt;
    }

    /**
     * @param mixed $dateEmprunt
     */
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
     * @return string
     */
    public function getEmprunteur(): string
    {
        return $this->emprunteur;
    }

    /**
     * @param string $emprunteur
     */
    public function setEmprunteur(string $emprunteur): void
    {
        $this->emprunteur = $emprunteur;
    }

    /**
     * @return string
     */
    public function getEmprunteurMail(): string
    {
        return $this->emprunteurMail;
    }

    /**
     * @param string $emprunteurMail
     */
    public function setEmprunteurMail(string $emprunteurMail): void
    {
        $this->emprunteurMail = $emprunteurMail;
    }


    public function __toString()
    {
        return $this->nom;
    }

}