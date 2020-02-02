<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Validate;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EditorEntityRepository")
 */
class EditorEntity
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @ORM\OneToMany(targetEntity="App\Entity\GameEntity", mappedBy="editor")
     * @Validate\Length(
     *      max="255", maxMessage="Le nom de l'éditeur ne peut excéder 255 caractères"
     * )
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Validate\Length(
     *      max="255", maxMessage="La nationalité de l'éditeur ne peut excéder 255 caractères"
     * )
     */
    private $nationality;

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

    public function getNationality(): ?string
    {
        return $this->nationality;
    }

    public function setNationality(string $nationality): self
    {
        $this->nationality = $nationality;

        return $this;
    }
}
