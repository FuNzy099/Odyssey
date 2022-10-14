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
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity=PrivateMessage::class, mappedBy="conversation")
     */
    private $privateMessages;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="conversations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $initiate;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="participation")
     * @ORM\JoinColumn(nullable=false)
     */
    private $participate;

    public function __construct()
    {
        $this->privateMessages = new ArrayCollection();
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

    public function getInitiate(): ?User
    {
        return $this->initiate;
    }

    public function setInitiate(?User $initiate): self
    {
        $this->initiate = $initiate;

        return $this;
    }

    public function getParticipate(): ?User
    {
        return $this->participate;
    }

    public function setParticipate(?User $participate): self
    {
        $this->participate = $participate;

        return $this;
    }
}
