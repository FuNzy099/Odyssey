<?php

namespace App\Entity;

use App\Repository\ConversationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ConversationRepository::class)
 */
class Conversation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")*
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity=PrivateMessage::class, mappedBy="conversation")
     */
    private $privateMessages;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $name = "titre conversation";

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="conversations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $conversationCreator;

    /**
     * @ORM\OneToMany(targetEntity=Participation::class, mappedBy="conversation", orphanRemoval=true)
     */
    private $participators;

    public function __construct()
    {
        $this->privateMessages = new ArrayCollection();
        $this->participators = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
            $privateMessage->setConversation($this);
        }

        return $this;
    }

    public function removePrivateMessage(PrivateMessage $privateMessage): self
    {
        if ($this->privateMessages->removeElement($privateMessage)) {
            // set the owning side to null (unless already changed)
            if ($privateMessage->getConversation() === $this) {
                $privateMessage->setConversation(null);
            }
        }

        return $this;
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

    public function getConversationCreator(): ?User
    {
        return $this->conversationCreator;
    }

    public function setConversationCreator(?User $conversationCreator): self
    {
        $this->conversationCreator = $conversationCreator;

        return $this;
    }

    /**
     * @return Collection<int, Participation>
     */
    public function getParticipators(): Collection
    {
        return $this->participators;
    }

    public function addParticipator(Participation $participator): self
    {
        if (!$this->participators->contains($participator)) {
            $this->participators[] = $participator;
            $participator->setConversation($this);
        }

        return $this;
    }

    public function removeParticipator(Participation $participator): self
    {
        if ($this->participators->removeElement($participator)) {
            // set the owning side to null (unless already changed)
            if ($participator->getConversation() === $this) {
                $participator->setConversation(null);
            }
        }

        return $this;
    }
}
