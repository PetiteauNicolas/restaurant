<?php

namespace App\Entity;

use App\Repository\RestaurantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: RestaurantRepository::class)]
class Restaurant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank(message: 'Le nom du restaurant ne peut pas être vide')]
    #[Assert\Length(
        max: 255,
        maxMessage: 'Le nom du restaurant ne peut pas contenir plus de {{ limit }} caractères.'
    )]
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[Assert\NotBlank(message: "L'adresse du restaurant ne peut pas être vide")]
    #[Assert\Length(
        max: 100,
        maxMessage: "L'adresse du restaurant ne peut pas contenir plus de {{ limit }} caractères."
    )]
    #[ORM\Column(length: 255)]
    private ?string $address = null;

    #[Assert\Length(
        max: 255,
        maxMessage: 'La description du restaurant ne peut pas contenir plus de {{ limit }} caractères.',
    )]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(nullable: true)]
    private ?int $rating = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $picture = null;

    #[Assert\Type(
        type: 'float',
        message: 'Veuillez uniquement saisir des chiffres.',
    )]
    #[Assert\Length(
        max: 5,
        maxMessage: 'Le prix moyen du restaurant ne peut pas contenir plus de {{ limit }} chiffres.',
    )]
    #[Assert\PositiveOrZero(
        message: 'Le prix ne peut pas être négatif.'
    )]
    #[ORM\Column(nullable: true)]
    private ?float $price = null;

    #[Assert\Length(
        max: 255,
        maxMessage: "L'adresse du site internet du restaurant ne peut pas contenir plus de {{ limit }} caractères.",
    )]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $website = null;

    #[ORM\Column(nullable: true)]
    private ?bool $already_done = null;

    /**
     * @var Collection<int, Food>
     */
    #[ORM\ManyToMany(targetEntity: Food::class, inversedBy: 'restaurants')]
    private Collection $food;

    #[ORM\ManyToOne(inversedBy: 'restaurant')]
    #[ORM\JoinColumn(nullable: false)]
    private ?City $city = null;

    #[Assert\Length(
        min: 0,
        max: 15,
        maxMessage: 'Le numéro de téléphone ne peut pas contenir plus de {{ limit }} caractères.',
    )]
    #[ORM\Column(nullable: true)]
    private ?string $phoneNumber = null;

    /**
     * @var Collection<int, Rating>
     */
    #[ORM\OneToMany(mappedBy: 'restaurant', targetEntity: Rating::class)]
    private Collection $ratings;

    public function __construct()
    {
        $this->food = new ArrayCollection();
        $this->ratings = new ArrayCollection();
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

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

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

    public function getRating(): ?int
    {
        return $this->rating;
    }

    public function setRating(?int $rating): self
    {
        $this->rating = $rating;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(?string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(?float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getWebsite(): ?string
    {
        return $this->website;
    }

    public function setWebsite(?string $website): self
    {
        $this->website = $website;

        return $this;
    }

    public function getAlreadyDone(): ?bool
    {
        return $this->already_done;
    }

    public function isAlreadyDone(): ?bool
    {
        return $this->already_done;
    }

    public function setAlreadyDone(?bool $already_done): self
    {
        $this->already_done = $already_done;

        return $this;
    }

    /**
     * @return Collection<int, Food>
     */
    public function getFood(): Collection
    {
        return $this->food;
    }

    public function addFood(Food $food): self
    {
        if (!$this->food->contains($food)) {
            $this->food->add($food);
        }

        return $this;
    }

    public function removeFood(Food $food): self
    {
        $this->food->removeElement($food);

        return $this;
    }

    public function getCity(): ?City
    {
        return $this->city;
    }

    public function setCity(?City $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(?string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    /**
     * @return Collection<int, Rating>
     */
    public function getRatings(): Collection
    {
        return $this->ratings;
    }

    public function addRating(Rating $rating): self
    {
        if (!$this->ratings->contains($rating)) {
            $this->ratings->add($rating);
            $rating->setRestaurant($this);
        }

        return $this;
    }

    public function removeRating(Rating $rating): self
    {
        if ($this->ratings->removeElement($rating)) {
            // set the owning side to null (unless already changed)
            if ($rating->getRestaurant() === $this) {
                $rating->setRestaurant(null);
            }
        }

        return $this;
    }

    public function getAverageRatings(): ?float
    {
        $ratings = 0;
        $i = 0;

        $allRating = $this->getRatings();

        if ($allRating->isEmpty()) {
            return null;
        }

        foreach ($allRating as $value) {
            $note = $value->getRating();
            $ratings += $note;
            ++$i;
        }

        if ($i >= 1) {
            return $ratings / $i;
        }

        return null;
    }
}
