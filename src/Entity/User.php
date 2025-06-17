<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'user')]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]

class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column]
    private bool $isVerified = false;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom = null;

    // On rend le champ 'photoprofil' et les autres champs optionnels
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $photoprofil = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $lieu = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $apropos = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $tel = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $facebook = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $linkedin = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $twiter = null;

    #[ORM\Column(nullable: true)]
    private ?array $competences = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $token = null;


    /**
     * @var Collection<int, Message>
     */
     #[ORM\OneToMany(mappedBy: 'author', targetEntity: Message::class, cascade: ['remove'])]
    private Collection $messages;

    /**
     * @var Collection<int, Innovation>
     */
    #[ORM\OneToMany(targetEntity: Innovation::class, mappedBy: 'user', cascade: ['remove'])]
    private Collection $innovations;

    
    public function __construct()
    {
        $this->messages = new ArrayCollection();
        $this->innovations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getPhotoprofil(): ?string
    {
        return $this->photoprofil;
    }

    public function setPhotoprofil(string $photoprofil): static
    {
        $this->photoprofil = $photoprofil;

        return $this;
    }

    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    public function setLieu(string $lieu): static
    {
        $this->lieu = $lieu;

        return $this;
    }

    public function getApropos(): ?string
    {
        return $this->apropos;
    }

    public function setApropos(string $apropos): static
    {
        $this->apropos = $apropos;

        return $this;
    }

    public function getTel(): ?string
    {
        return $this->tel;
    }

    public function setTel(string $tel): static
    {
        $this->tel = $tel;

        return $this;
    }

    public function getFacebook(): ?string
    {
        return $this->facebook;
    }

    public function setFacebook(string $facebook): static
    {
        $this->facebook = $facebook;

        return $this;
    }

    public function getLinkedin(): ?string
    {
        return $this->linkedin;
    }

    public function setLinkedin(string $linkedin): static
    {
        $this->linkedin = $linkedin;

        return $this;
    }

    public function getTwiter(): ?string
    {
        return $this->twiter;
    }

    public function setTwiter(string $twiter): static
    {
        $this->twiter = $twiter;

        return $this;
    }

    public function getCompetences(): ?array
    {
        return $this->competences;
    }

    public function setCompetences(?array $competences): static
    {
        $this->competences = $competences;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token): self
    {
        $this->token = $token;
        return $this;
    }

    /**
     * @return Collection<int, Innovation>
     */
    public function getInnovations(): Collection
    {
        return $this->innovations;
    }

    public function addInnovation(Innovation $innovation): static
    {
        if (!$this->innovations->contains($innovation)) {
            $this->innovations->add($innovation);
            $innovation->setUser($this);
        }

        return $this;
    }

    public function removeInnovation(Innovation $innovation): static
    {
        if ($this->innovations->removeElement($innovation)) {
            // set the owning side to null (unless already changed)
            if ($innovation->getUser() === $this) {
                $innovation->setUser(null);
            }
        }

        return $this;
    }

    
}
