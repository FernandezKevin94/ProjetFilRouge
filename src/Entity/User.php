<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
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

    #[ORM\Column(length: 30)]
    private ?string $matricule = null;

    #[ORM\Column(length: 255)]
    private ?string $firstName = null;

    #[ORM\Column(length: 255)]
    private ?string $lastName = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $birthday = null;

    #[ORM\Column(length: 30)]
    private ?string $telephone = null;

    #[ORM\Column(length: 255)]
    private ?string $service = null;

    #[ORM\Column(length: 255)]
    private ?string $speciality = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $photo = null;

    /**
     * @var Collection<int, Adresse>
     */
    #[ORM\OneToMany(targetEntity: Adresse::class, mappedBy: 'user')]
    private Collection $adresses;

    /**
     * @var Collection<int, Projet>
     */
    #[ORM\ManyToMany(targetEntity: Projet::class, mappedBy: 'user')]
    private Collection $projets;

    /**
     * @var Collection<int, ChefDeProjet>
     */
    #[ORM\OneToMany(targetEntity: ChefDeProjet::class, mappedBy: 'user')]
    private Collection $chefDeProjets;

    /**
 * @var Collection<int, Tache>
 */
   #[ORM\OneToMany(targetEntity: Tache::class, mappedBy: 'user')]
    private Collection $taches;

     /**
     * @var Collection<int, Notifications>
     */
    #[ORM\OneToMany(targetEntity: Notifications::class, mappedBy: 'user')]
    private Collection $notifications;

   

    #[ORM\Column(nullable: true)]
    private ?bool $is_Affected = null;

    #[ORM\Column(type: 'boolean', options: ['default' => true])]
    private bool $isAvailable = true;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $token = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $tokenExpiredAt = null;

    public function __construct()
    {
        $this->adresses = new ArrayCollection();
        $this->projets = new ArrayCollection();
        $this->chefDeProjets = new ArrayCollection();
        $this->taches = new ArrayCollection();
        $this->notifications = new ArrayCollection();
    }

    public function __toString(){
        return $this->firstName . ' ' . $this->lastName;
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
     *
     * @return list<string>
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

    public function getMatricule(): ?string
    {
        return $this->matricule;
    }

    public function setMatricule(string $matricule): static
    {
        $this->matricule = $matricule;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getBirthday(): ?\DateTimeInterface
    {
        return $this->birthday;
    }

    public function setBirthday(\DateTimeInterface $birthday): static
    {
        $this->birthday = $birthday;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): static
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getService(): ?string
    {
        return $this->service;
    }

    public function setService(string $service): static
    {
        $this->service = $service;

        return $this;
    }

    public function getSpeciality(): ?string
    {
        return $this->speciality;
    }

    public function setSpeciality(string $speciality): static
    {
        $this->speciality = $speciality;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): static
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * @return Collection<int, Adresse>
     */
    public function getAdresses(): Collection
    {
        return $this->adresses;
    }

    public function addAdress(Adresse $adress): static
    {
        if (!$this->adresses->contains($adress)) {
            $this->adresses->add($adress);
            $adress->setUser($this);
        }

        return $this;
    }

    public function removeAdress(Adresse $adress): static
    {
        if ($this->adresses->removeElement($adress)) {
            // set the owning side to null (unless already changed)
            if ($adress->getUser() === $this) {
                $adress->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Projet>
     */
    public function getProjets(): Collection
    {
        return $this->projets;
    }

    public function addProjet(Projet $projet): static
    {
        if (!$this->projets->contains($projet)) {
            $this->projets->add($projet);
            $projet->addUser($this);
        }

        return $this;
    }

    public function removeProjet(Projet $projet): static
    {
        if ($this->projets->removeElement($projet)) {
            $projet->removeUser($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, ChefDeProjet>
     */
    public function getChefDeProjets(): Collection
    {
        return $this->chefDeProjets;
    }

    public function addChefDeProjet(ChefDeProjet $chefDeProjet): static
    {
        if (!$this->chefDeProjets->contains($chefDeProjet)) {
            $this->chefDeProjets->add($chefDeProjet);
            $chefDeProjet->setUser($this);
        }

        return $this;
    }

    public function removeChefDeProjet(ChefDeProjet $chefDeProjet): static
    {
        if ($this->chefDeProjets->removeElement($chefDeProjet)) {
            // set the owning side to null (unless already changed)
            if ($chefDeProjet->getUser() === $this) {
                $chefDeProjet->setUser(null);
            }
        }

        return $this;
    }

    /**
 * @return Collection<int, Tache>
 */
public function getTaches(): Collection
{
    return $this->taches;
}

public function addTache(Tache $tache): static
{
    if (!$this->taches->contains($tache)) {
        $this->taches->add($tache);
        $tache->setUser($this);
    }

    return $this;
}

public function removeTache(Tache $tache): static
{
    if ($this->taches->removeElement($tache)) {
        // set the owning side to null (unless already changed)
        if ($tache->getUser() === $this) {
            $tache->setUser(null);
        }
    }

    return $this;
}

    public function isAffected(): ?bool
    {
        return $this->is_Affected;
    }

    public function setIsAffected(?bool $is_Affected): static
    {
        $this->is_Affected = $is_Affected;

        return $this;
    }

    public function isAvailable(): bool
    {
    
        return $this->isAvailable;
    }

    public function setAvailable(bool $available): self
    {
        $this->isAvailable = $available;  
        return $this;  
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token): static
    {
        $this->token = $token;

        return $this;
    }

    public function getTokenExpiredAt(): ?\DateTimeInterface
    {
        return $this->tokenExpiredAt;
    }

    public function setTokenExpiredAt(?\DateTimeInterface $tokenExpiredAt): static
    {
        $this->tokenExpiredAt = $tokenExpiredAt;

        return $this;
    }

      /** 
     *@return Collection<int, Notification>
    */
  public function getNotifications(): Collection{
      return $this->notifications;}

    public function addNotification(Notification $notification): static
    {
        if (!$this->notifications->contains($notification)) {
            $this->notifications->add($notification);
            $notification->setUser($this);
        }

        return $this;
    }

    public function removeNotification(Notification $notification): static
    {
        if ($this->notifications->removeElement($notification)) {
            // set the owning side to null (unless already changed)
            if ($notification->getUser() === $this) {
                $notification->setUser(null);
            }
        }

        return $this;
    }
    
}


