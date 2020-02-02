<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Validate;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GameEntityRepository")
 */
class GameEntity
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Validate\Length(
     *      max="255", maxMessage="Le nom du jeu ne peut excéder 255 caractères"
     * )
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Validate\Length(
     *      max="255", maxMessage="Le nom de la plateforme du jeu ne peut excéder 255 caractères"
     * )
     */
    private $plateform;

    /**
     * @ORM\Column(type="string", length=5000)
     * @Validate\Length(
     *      max="255", maxMessage="La description du jeu ne peut excéder 5000 caractères"
     * )
     */
    private $description;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $releaseDate;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\EditorEntity", inversedBy="name")
     * @ORM\JoinColumn(nullable=true)
     */
    private $editor;

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

    public function getPlateform(): ?string
    {
        return $this->plateform;
    }

    public function setPlateform(string $plateform): self
    {
        $this->plateform = $plateform;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getReleaseDate(): ?\DateTimeInterface
    {
        return $this->releaseDate;
    }

    public function setReleaseDate(?\DateTimeInterface $releaseDate): self
    {
        $this->releaseDate = $releaseDate;

        return $this;
    }

    public function getEditor(): ?EditorEntity
    {
        return $this->editor;
    }

    public function setEditor(EditorEntity $editor): self
    {
        $this->editor = $editor;

        return $this;
    }

    public function setEditorToNull(): self
    {
        $this->editor = null;
        return $this;
    }
}
