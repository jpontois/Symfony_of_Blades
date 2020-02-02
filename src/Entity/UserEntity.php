<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Validate;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserEntityRepository")
 * @UniqueEntity(fields="email",message="L'email est déjà utilisé par un autre profil")
 */
class UserEntity implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Validate\Email(message="L'email est invalide")
     * @Validate\NotBlank(message="L'email doit être renseigné")
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     * @Validate\Length(
     *      min="4", minMessage="Le mot de passe doit faire au minimum 4 caractères",
     *      max="255", maxMessage="Nous ne sommes malheuresement pas en mesure d'assurer de telles niveau de sécurité. Aller, un petit effort, un mot de passe de 255 caractères max c'est déjà suffisant non ? ;)"
     * )
     * */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     * @Validate\Length(
     *      max="255", maxMessage="Vous avez un nom sacrément long ! :O Serait-il possible de l'abréger un peu pour soulager notre base de donnée ? Moins de 255 caractères ce serait parfait !"
     * )
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Validate\Length(
     *      max="255", maxMessage="Vous avez un prénom sacrément long ! :O Serait-il possible de l'abréger un peu pour soulager notre base de donnée ? Moins de 255 caractères ce serait parfait !"
     * )
     */
    private $lastName;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $birthDate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $creationDate;

    /**
     * @ORM\Column(type="simple_array")
     */
    private $roles;

    public function __construct()
    {
        $this->roles = ['ROLE_USER'];
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

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getBirthDate(): ?\DateTimeInterface
    {
        return $this->birthDate;
    }

    public function setBirthDate(?\DateTimeInterface $birthDate): self
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creationDate;
    }

    public function setCreationDate(\DateTimeInterface $creationDate): self
    {
        $this->creationDate = $creationDate;
        return $this;
    }

    public function getSalt() {
        return null;
    }

    public function getRoles() {
        return $this->roles;
    }

    public function setRoles(array $roles): UserEntity
    {
        $this->roles = $roles;
        return $this;
    }

    public function getUserName() {}
    public function eraseCredentials() {}
}
