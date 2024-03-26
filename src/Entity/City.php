<?php

namespace App\Entity;

use App\Repository\FilmRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity(repositoryClass=FilmRepository::class)
 */
class Film
{
    public function __toString()
    {
        return $this->name;
    }
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;


    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date_ajout;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date_de_sortie;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $jaquette;

    /**
     * @var Collection<int, Realisateur>
     */
    #[ORM\ManyToMany(targetEntity: Realisateur::class, inversedBy: 'film')]
    private Collection $realisateur;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $wallpaper;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $vu;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $note;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $duree;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $synopsis;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $bande_annonce;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $note_spectateur;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $photo;

    /**
     * @ORM\ManyToMany(targetEntity=Genre::class, inversedBy="film")
     */
    private $genre;

    #[ORM\ManyToMany(targetEntity: Acteur::class, inversedBy: 'film')]
    private $acteur;

    #[ORM\ManyToOne(inversedBy: 'film')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Pays $pays = null;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $avertissement;

    public function __construct()
    {
        $this->realisateur = new ArrayCollection();
        $this->genre = new ArrayCollection();
        $this->acteur = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }


    public function getDateAjout(): ?\DateTimeInterface
    {
        return $this->date_ajout;
    }

    public function setDateAjout(?\DateTimeInterface $date_ajout): self
    {
        $this->date_ajout = $date_ajout;

        return $this;
    }

    public function getDateDeSortie(): ?\DateTimeInterface
    {
        return $this->date_de_sortie;
    }

    public function setDateDeSortie(?\DateTimeInterface $date_de_sortie): self
    {
        $this->date_de_sortie = $date_de_sortie;

        return $this;
    }

    public function getJaquette(): ?string
    {
        return $this->jaquette;
    }

    public function setJaquette(?string $jaquette): self
    {
        $this->jaquette = $jaquette;

        return $this;
    }

    /**
     * @return Collection<int, Realisateur>
     */
    public function getRealisateur(): Collection
    {
        return $this->realisateur;
    }

    public function addRealisateur(Realisateur $realisateur): self
    {
        if (!$this->realisateur->contains($realisateur)) {
            $this->realisateur[] = $realisateur;
            $realisateur->addFilm($this);
        }

        return $this;
    }

    public function removeRealisateur(Realisateur $realisateur): self
    {
        if ($this->realisateur->removeElement($realisateur)) {
            $realisateur->removeFilm($this);
        }

        return $this;
    }

    public function getWallpaper(): ?string
    {
        return $this->wallpaper;
    }

    public function setWallpaper(?string $wallpaper): self
    {
        $this->wallpaper = $wallpaper;

        return $this;
    }

    public function isVu(): ?bool
    {
        return $this->vu;
    }

    public function setVu(?bool $vu): self
    {
        $this->vu = $vu;

        return $this;
    }

    public function getNote(): ?float
    {
        return $this->note;
    }

    public function setNote(?float $note): self
    {
        $this->note = $note;

        return $this;
    }


    public function getDuree(): ?\DateTimeInterface
    {
        return $this->duree;
    }

    public function setDuree(?\DateTimeInterface $duree): self
    {
        $this->duree = $duree;

        return $this;
    }

    public function getSynopsis(): ?string
    {
        return $this->synopsis;
    }

    public function setSynopsis(?string $synopsis): self
    {
        $this->synopsis = $synopsis;

        return $this;
    }

    public function getBandeAnnonce(): ?string
    {
        return $this->bande_annonce;
    }

    public function setBandeAnnonce(?string $bande_annonce): self
    {
        $this->bande_annonce = $bande_annonce;

        return $this;
    }

    public function getNoteSpectateur(): ?float
    {
        return $this->note_spectateur;
    }

    public function setNoteSpectateur(?float $note_spectateur): self
    {
        $this->note_spectateur = $note_spectateur;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * @return Collection<int, Genre>
     */
    public function getGenre(): Collection
    {
        return $this->genre;
    }

    public function addGenre(Genre $genre): self
    {
        if (!$this->genre->contains($genre)) {
            $this->genre[] = $genre;
            $genre->addFilm($this);
        }

        return $this;
    }

    public function removeGenre(Genre $genre): self
    {
        if ($this->genre->removeElement($genre)) {
            $genre->removeFilm($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Acteur>
     */
    public function getActeur(): Collection
    {
        return $this->acteur;
    }

    public function addActeur(Acteur $acteur): self
    {
        if (!$this->acteur->contains($acteur)) {
            $this->acteur[] = $acteur;
            $acteur->addFilm($this);
        }

        return $this;
    }

    public function removeActeur(Acteur $acteur): self
    {
        if ($this->acteur->removeElement($acteur)) {
            $acteur->removeFilm($this);
        }

        return $this;
    }

    public function getPays(): ?Pays
    {
        return $this->pays;
    }

    public function setPays(?Pays $pays): self
    {
        $this->pays = $pays;

        return $this;
    }

    public function getAvertissement(): ?string
    {
        return $this->avertissement;
    }

    public function setAvertissement(string $avertissement): self
    {
        $this->avertissement = $avertissement;

        return $this;
    }
}
