<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=25, unique=true)
     * @Assert\Length(
     *      min = 4,
     *      max = 25,
     *      minMessage = "Votre nom d'utilisateur doit contenir au moins 4 caractères.",
     *      maxMessage = "Votre nom d'utilisateur ne peut pas contenir plus de 25 caractères."
     * )
     */
    private $pseudonyme;

    /**
     * @ORM\Column(type="datetime")
     */
    private $registrationDate;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isVerified = false;

    /**
     * @ORM\OneToMany(targetEntity=Article::class, mappedBy="user")
     */
    private $articles;

    /**
     * @ORM\OneToMany(targetEntity=Event::class, mappedBy="userCreator", orphanRemoval=true)
     */
    private $createEvents;

    /**
     * @ORM\ManyToMany(targetEntity=Event::class, inversedBy="users")
     */
    private $registrationEvents;

    /**
     * @ORM\Column(type="string", length=14)
     */
    private $phoneNumber;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $avatar = "default-avatar.png";

    /**
     * @ORM\OneToMany(targetEntity=PrivateMessage::class, mappedBy="user")
     */
    private $privateMessages;

    /**
     * @ORM\OneToMany(targetEntity=Participation::class, mappedBy="sender")
     */
    private $senderParticipations;

    /**
     * @ORM\OneToMany(targetEntity=Participation::class, mappedBy="recipient")
     */
    private $recipientParticipations;

    /**
     * @ORM\ManyToMany(targetEntity=Conversation::class)
     */
    private $userConversations;

    public function __construct()
    {
        // new DataTime permet lors de la création d'un compte utilisateur d'inscrire en base de donnée la date est l'heure de l'inscription
        $this -> registrationDate = new DateTime();
        
        $this->articles = new ArrayCollection();
        $this->registrationEvents = new ArrayCollection();
        $this->createEvents = new ArrayCollection();
        $this->privateMessages = new ArrayCollection();
        $this->senderParticipations = new ArrayCollection();
        $this->recipientParticipations = new ArrayCollection();
        $this->userConversations = new ArrayCollection();
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
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
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

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getPseudonyme(): ?string
    {
        return $this->pseudonyme;
    }

    public function setPseudonyme(string $pseudonyme): self
    {
        /*
            ucfirst permet de mettre le premier caractère en majuscule
            strtolower renvoie une chaine en minuscules
        */
        $this->pseudonyme = ucfirst(strtolower($pseudonyme));

        return $this;
    }

    public function getRegistrationDate(): ?\DateTimeInterface
    {
        return $this->registrationDate;
    }

    public function setRegistrationDate(\DateTimeInterface $registrationDate): self
    {
        $this->registrationDate = $registrationDate;

        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    /**
     * @return Collection<int, Article>
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function addArticle(Article $article): self
    {
        if (!$this->articles->contains($article)) {
            $this->articles[] = $article;
            $article->setUser($this);
        }

        return $this;
    }

    public function removeArticle(Article $article): self
    {
        if ($this->articles->removeElement($article)) {
            // set the owning side to null (unless already changed)
            if ($article->getUser() === $this) {
                $article->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Event>
     */
    public function getRegistrationEvents(): Collection
    {
        return $this->registrationEvents;
    }

    public function addRegistrationEvent(Event $registrationEvent): self
    {
        if (!$this->registrationEvents->contains($registrationEvent)) {
            $this->registrationEvents[] = $registrationEvent;
        }

        return $this;
    }

    public function removeRegistrationEvent(Event $registrationEvent): self
    {
        $this->registrationEvents->removeElement($registrationEvent);

        return $this;
    }

    /**
     * @return Collection<int, Event>
     */
    public function getCreateEvents(): Collection
    {
        return $this->createEvents;
    }

    public function addCreateEvent(Event $createEvent): self
    {
        if (!$this->createEvents->contains($createEvent)) {
            $this->createEvents[] = $createEvent;
            $createEvent->setUserCreator($this);
        }

        return $this;
    }

    public function removeCreateEvent(Event $createEvent): self
    {
        if ($this->createEvents->removeElement($createEvent)) {
            // set the owning side to null (unless already changed)
            if ($createEvent->getUserCreator() === $this) {
                $createEvent->setUserCreator(null);
            }
        }

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }
   
    /**
     * @return Collection<int, PrivateMessage>
     */
    public function getPrivateMessages(): Collection
    {
        return $this->privateMessages;
    }

    public function addPrivateMessage(PrivateMessage $privateMessage): self
    {
        if (!$this->privateMessages->contains($privateMessage)) {
            $this->privateMessages[] = $privateMessage;
            $privateMessage->setUser($this);
        }

        return $this;
    }

    public function removePrivateMessage(PrivateMessage $privateMessage): self
    {
        if ($this->privateMessages->removeElement($privateMessage)) {
            // set the owning side to null (unless already changed)
            if ($privateMessage->getUser() === $this) {
                $privateMessage->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Participation>
     */
    public function getSenderParticipations(): Collection
    {
        return $this->senderParticipations;
    }

    public function addSenderParticipations(Participation $participationSender): self
    {
        if (!$this->senderParticipations->contains($participationSender)) {
            $this->senderParticipations[] = $participationSender;
            $participationSender->setSender($this);
        }

        return $this;
    }

    public function removeSenderParticipations(Participation $participationSender): self
    {
        if ($this->senderParticipations->removeElement($participationSender)) {
            // set the owning side to null (unless already changed)
            if ($participationSender->getSender() === $this) {
                $participationSender->setSender(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Participation>
     */
    public function getRecipientParticipations(): Collection
    {
        return $this->recipientParticipations;
    }

    public function addRecipientParticipation(Participation $recipientParticipation): self
    {
        if (!$this->recipientParticipations->contains($recipientParticipation)) {
            $this->recipientParticipations[] = $recipientParticipation;
            $recipientParticipation->setRecipient($this);
        }

        return $this;
    }

    public function removeRecipientParticipation(Participation $recipientParticipation): self
    {
        if ($this->recipientParticipations->removeElement($recipientParticipation)) {
            // set the owning side to null (unless already changed)
            if ($recipientParticipation->getRecipient() === $this) {
                $recipientParticipation->setRecipient(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Conversation>
     */
    public function getUserConversations(): Collection
    {
        return $this->userConversations;
    }

    public function addUserConversation(Conversation $userConversation): self
    {
        if (!$this->userConversations->contains($userConversation)) {
            $this->userConversations[] = $userConversation;
        }

        return $this;
    }

    public function removeUserConversation(Conversation $userConversation): self
    {
        $this->userConversations->removeElement($userConversation);

        return $this;
    }
}
