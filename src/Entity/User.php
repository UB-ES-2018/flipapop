<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Serializable;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\User\UserInterface;
use Vich\UploaderBundle\Entity\File as EmbeddedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity("email")
 * @ORM\Table("users")
 * @Vich\Uploadable
 */
class User implements UserInterface, Serializable
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
     * @ORM\Column(type="string", length=255)
     */
    private $surname;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $email;


    private $plainPassword;

    private $salt;

    /**
     * @ORM\Column(type="string", length=4000)
     */
    private $password;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;
  
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Product", mappedBy="usuario", orphanRemoval=true)
     */
    private $products;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @Vich\UploadableField(mapping="user_image", fileNameProperty="image.name", size="image.size", mimeType="image.mimeType", originalName="image.originalName", dimensions="image.dimensions")
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
     * @ORM\ManyToMany(targetEntity="App\Entity\Product", inversedBy="likedUsers")
     */
    private $likedProducts;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ComentarioProducto", mappedBy="usuario", orphanRemoval=true)
     */
    private $comentarioProductos;

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

    public function __construct()
    {
        $this->products = new ArrayCollection();
        $this->image = new EmbeddedFile();
        $this->likedProducts = new ArrayCollection();
        $this->comentarioProductos = new ArrayCollection();
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

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): self
    {
        $this->surname = $surname;

        return $this;
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

    /**
     * @return mixed
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * @param mixed $plainPassword
     */
    public function setPlainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;
    }

    public function eraseCredentials()
    {
    }

    public function getRoles()
    {
        return array('ROLE_USER');
    }

    public function getSalt()
    {

    }

    public function getUsername()
    {
        return $this->email;
    }

    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->name,
            $this->surname,
            $this->email,
            $this->password,
        ));
    }

    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->name,
            $this->surname,
            $this->email,
            $this->password,
            ) = unserialize($serialized, array('allowed_classes' => false));    }


    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }
  
    /**
     * @return Collection|Product[]
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->setUsuario($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->contains($product)) {
            $this->products->removeElement($product);
            // set the owning side to null (unless already changed)
            if ($product->getUsuario() === $this) {
                $product->setUsuario(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Product[]
     */
    public function getLikedProducts(): Collection
    {
        return $this->likedProducts;
    }

    public function addLikedProduct(Product $likedProduct): self
    {
        if (!$this->likedProducts->contains($likedProduct)) {
            $this->likedProducts->add($likedProduct);
        }

        return $this;
    }

    public function removeLikedProduct(Product $likedProduct): self
    {
        if ($this->likedProducts->contains($likedProduct)) {
            $this->likedProducts->removeElement($likedProduct);
            $likedProduct->removeLikedUser($this);
        }

        return $this;
    }

    /**
     * @return Collection|ComentarioProducto[]
     */
    public function getComentarioProductos(): Collection
    {
        return $this->comentarioProductos;
    }

    public function addComentarioProducto(ComentarioProducto $comentarioProducto): self
    {
        if (!$this->comentarioProductos->contains($comentarioProducto)) {
            $this->comentarioProductos[] = $comentarioProducto;
            $comentarioProducto->setUsuario($this);
        }

        return $this;
    }

    public function removeComentarioProducto(ComentarioProducto $comentarioProducto): self
    {
        if ($this->comentarioProductos->contains($comentarioProducto)) {
            $this->comentarioProductos->removeElement($comentarioProducto);
            // set the owning side to null (unless already changed)
            if ($comentarioProducto->getUsuario() === $this) {
                $comentarioProducto->setUsuario(null);
            }
        }

        return $this;
    }

    public function getFullUserName(){
        return $this->name." ".$this->surname;
    }

}
