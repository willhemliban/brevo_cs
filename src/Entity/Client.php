<?php
namespace App\Entity;

use App\Repository\ClientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClientRepository::class)]
#[ORM\Table(name: 'clients')]
class Client
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    private int $clientId;

    #[ORM\OneToMany(targetEntity: ClientRecord::class, mappedBy: 'client', cascade: ['persist', 'remove'])]
    private Collection $records;

    #[ORM\Column(type: 'integer', nullable: true)]
    private int $score;

    #[ORM\Column(type: 'integer', nullable: true)]
    private int $totalEmailSent;

    #[ORM\Column(type: 'integer', nullable: true)]
    private int $category;

    public function __construct()
    {
        $this->records = new ArrayCollection();
    }

    public function getClientId(): ?int
    {
        return $this->clientId;
    }

    public function setClientId(int $clientId): self
    {
        $this->clientId = $clientId;

        return $this;
    }

    public function getScore(): ?int
    {
        return $this->score;
    }

    public function setScore(int $score): self
    {
        $this->score = $score;

        return $this;
    }

    public function getTotalEmailSent(): ?int
    {
        return $this->totalEmailSent;
    }

    public function setTotalEmailSent(int $totalEmailSent): self
    {
        $this->totalEmailSent = $totalEmailSent;

        return $this;
    }

    public function getRecords(): Collection
    {
        return $this->records;
    }

    public function addRecord(ClientRecord $record): self
    {
        if (!$this->records->contains($record)) {
            $this->records[] = $record;
            $record->setClient($this);
        }

        return $this;
    }

    public function removeRecord(ClientRecord $record): self
    {
        if ($this->records->removeElement($record)) {
            // set the owning side to null (unless already changed)
            if ($record->getClient() === $this) {
                $record->setClient(null);
            }
        }

        return $this;
    }

    public function getCategory(): ?int
    {
        return $this->category;
    }

    public function setCategory(int $category): self
    {
        $this->category = $category;

        return $this;
    }
}
