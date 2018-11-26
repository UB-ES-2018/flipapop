<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use function is_null;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Entity\File as EmbeddedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 * @Vich\Uploadable
 */
class Product
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=800)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="products")
     * @ORM\JoinColumn(nullable=false)
     */
    private $usuario;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @Vich\UploadableField(mapping="product_image", fileNameProperty="image.name", size="image.size", mimeType="image.mimeType", originalName="image.originalName", dimensions="image.dimensions")
     *
     * @var File
     */
    private $imageFile;

    /**
     * @ORM\Embedded(class="Vich\UploaderBundle\Entity\File")
     *
     * @var EmbeddedFile
     */
    private $image;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @var \DateTime
     */
    private $updatedAt;

    /**
     *
     * NOTE: The value will be between 1 and 3.
     *
     * @ORM\Column(type="integer")
     */
    private $visibility;

    const VISIBLE_ALL = 1;
    const VISIBLE_LOGGED = 2;
    const VISIBLE_ME = 3;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", mappedBy="likedProducts")
     */
    private $likedUsers;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ComentarioProducto", mappedBy="product", orphanRemoval=true)
     */
    private $comentarios;

    /**
     *
     * NOTE: The product is sold or not.
     *
     * @ORM\Column(type="boolean")
     */
    private $sold;


    public function __construct()
    {
        $this->image = new EmbeddedFile();
        $this->visibility = $this::VISIBLE_ALL;
        $this->likedUsers = new ArrayCollection();
        $this->comentarios = new ArrayCollection();
        $this->sold = false;
    }

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the  update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|UploadedFile $image
     */
    public function setImageFile(?File $image = null)
    {
        $this->imageFile = $image;

        if (null !== $image) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImage(EmbeddedFile $image)
    {
        $this->image = $image;
    }

    public function getImage(): ?EmbeddedFile
    {
        return $this->image;
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

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getUsuario(): ?User
    {
        return $this->usuario;
    }

    public function setUsuario(?User $usuario): self
    {
        $this->usuario = $usuario;

        return $this;
    }


    public function getVisibility(): ?int
    {
        return $this->visibility;
    }

    public function setVisibility(int $visibility): self
    {
        $this->visibility = $visibility;
        return $this;
    }


    public function getSold()
    {
        return $this->sold;
    }

    public function setSold($sold): self
    {
        $this->sold = $sold;
        return $this;
    }

    public function changeSold(): self
    {
        $this->sold = !$this->sold;
        return $this;
    }

    public function isLikedBy(User $user){
        foreach ($this->likedUsers as $u){
            if($u->getId() === $user->getId()){
                return true;
            }
        }
        return false;
    }

    /**
     * @return Collection|User[]
     */
    public function getLikedUsers(): Collection
    {
        return $this->likedUsers;
    }

    public function addLikedUser(User $likedUser): self
    {
        if (!$this->likedUsers->contains($likedUser)) {
            $likedUser->addLikedProduct($this);
            $this->likedUsers->add($likedUser);
        }

        return $this;
    }


    /**
     * Return true IF:
     * 1. visibility = VISIBLE_ALL OR
     * 2. visibility = VISIBLE_LOGGED AND user is logged in OR
     * 3. I am the user.
     *
     * @param User|null $user
     * @return bool|null
     */
    public function canView(?User $user): ?bool
    {
        if($this->getVisibility() === self::VISIBLE_ALL) return true;
        if($this->getVisibility() === self::VISIBLE_LOGGED and !is_null($user)) return true;
        if($this->getVisibility() === self::VISIBLE_ME and !is_null($user) and $user->getId() === $this->usuario->getId())return true;
        return false;
    }
  
    public function removeLikedUser(User $likedUser): self
    {
        if ($this->likedUsers->contains($likedUser)) {
            $this->likedUsers->removeElement($likedUser);
            $likedUser->removeLikedProduct($this);
        }

        return $this;

    }

    /**
     * @return Collection|ComentarioProducto[]
     */
    public function getComentarios(): Collection
    {
        return $this->comentarios;
    }

    public function addComentario(ComentarioProducto $comentario): self
    {
        if (!$this->comentarios->contains($comentario)) {
            $this->comentarios[] = $comentario;
            $comentario->setProduct($this);
        }

        return $this;
    }

    public function removeComentario(ComentarioProducto $comentario): self
    {
        if ($this->comentarios->contains($comentario)) {
            $this->comentarios->removeElement($comentario);
            // set the owning side to null (unless already changed)
            if ($comentario->getProduct() === $this) {
                $comentario->setProduct(null);
            }
        }

        return $this;
    }
}
