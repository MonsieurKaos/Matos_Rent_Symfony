<?php
// src/Entity/User.php

namespace App\Entity;

use Stringable;
use DateTimeInterface;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity]
class User implements UserInterface, PasswordAuthenticatedUserInterface, Stringable
{
    #[ORM\Column(name: 'id', type: 'integer')]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    protected ?int $id = null;
    #[Assert\NotBlank]
    #[ORM\Column(name: 'nom', type: 'string', length: 35)]
    protected ?string $nom = null;
    #[Assert\NotBlank]
    #[ORM\Column(name: 'prenom', type: 'string', length: 35)]
    protected ?string $prenom = null;
    #[Assert\NotBlank]
    #[ORM\Column(name: 'email', type: 'string', length: 55)]
    protected ?string $email = null;
    #[Assert\NotBlank]
    #[ORM\Column(name: 'matricule', type: 'string', length: 9)]
    protected ?string $matricule = null;
    #[Assert\NotBlank]
    #[ORM\Column(name: 'roles', type: 'simple_array', length: 100)]
    protected array $roles;
    #[ORM\Column(name: 'password', type: 'string', length: 100)]
    protected $password;
    #[ORM\Column(name: 'rue', type: 'string', length: 100, nullable: true)]
    protected $rue;
    #[ORM\Column(name: 'cp', type: 'string', length: 6, nullable: true)]
    protected $cp;
    #[ORM\Column(name: 'ville', type: 'string', length: 55, nullable: true)]
    protected $ville;
    #[ORM\Column(name: 'latitude', type: 'float', nullable: true)]
    protected $latitude;
    #[ORM\Column(name: 'longitude', type: 'float', nullable: true)]
    protected $longitude;

    protected  $passwordConfirm;
    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string|null
     */
    public function getNom(): ?string
    {
        return $this->nom;
    }

    /**
     * @param string|null $nom
     */
    public function setNom(?string $nom): void
    {
        $this->nom = $nom;
    }

    /**
     * @return string|null
     */
    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    /**
     * @param string|null $prenom
     */
    public function setPrenom(?string $prenom): void
    {
        $this->prenom = $prenom;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string|null $email
     */
    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string|null
     */
    public function getMatricule(): ?string
    {
        return $this->matricule;
    }

    /**
     * @param string|null $matricule
     */
    public function setMatricule(?string $matricule): void
    {
        $this->matricule = $matricule;
    }

    public function setRoles(mixed $roles): static
    {
        $listeRoles = '';
        $first = true;
        $insideRoles = [];
        if (is_string($roles)) {
            $insideRoles = [$roles];
        } else {
            $insideRoles = $roles;
        }
        foreach ($insideRoles as $role) {
            if ($first) {
                $listeRoles .= $role;
                $first = false;
            } else {
                $listeRoles = ',' . $role;
            }
        }
        $this->roles = $roles;
        return $this;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * @return mixed
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password): void
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getPasswordConfirm()
    {
        return $this->passwordConfirm;
    }

    /**
     * @param mixed $passwordConfirm
     */
    public function setPasswordConfirm($passwordConfirm): void
    {
        $this->passwordConfirm = $passwordConfirm;
    }

    /**
     * @return mixed
     */
    public function getRue()
    {
        return $this->rue;
    }

    /**
     * @param mixed $rue
     */
    public function setRue($rue): void
    {
        $this->rue = $rue;
    }

    /**
     * @return mixed
     */
    public function getCp()
    {
        return $this->cp;
    }

    /**
     * @param mixed $cp
     */
    public function setCp($cp): void
    {
        $this->cp = $cp;
    }

    /**
     * @return mixed
     */
    public function getVille()
    {
        return $this->ville;
    }

    /**
     * @param mixed $ville
     */
    public function setVille($ville): void
    {
        $this->ville = $ville;
    }

    /**
     * @return mixed
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @param mixed $latitude
     */
    public function setLatitude($latitude): void
    {
        $this->latitude = $latitude;
    }

    /**
     * @return mixed
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @param mixed $longitude
     */
    public function setLongitude($longitude): void
    {
        $this->longitude = $longitude;
    }



    /**
     * Get the value of Prenom
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->nom . ' ' . $this->prenom;
    }
    public function getUsername(): string
    {
        return $this->email;
    }
    public function getUserIdentifier(): string
    {
        return $this->email;
    }
    public function eraseCredentials()
    {
    }
    public function getSalt()
    {
        return null;
    }


}
