<?php

namespace App\Entity;

use App\Repository\ClientRecordRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClientRecordRepository::class)]
#[ORM\Table(name: 'client_records')]

class ClientRecord
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\ManyToOne(inversedBy: 'records')]
    #[ORM\JoinColumn(name: 'client_id', referencedColumnName: 'client_id', nullable: false)]
    private Client $client;

    #[ORM\Column(type: 'integer')]
    private int $totalSent;

    #[ORM\Column(type: 'float')]
    private float $openRate;

    #[ORM\Column(type: 'float')]
    private float $unsubscriptionRate;

    #[ORM\Column(type: 'float')]
    private float $bounceRate;

    #[ORM\Column(type: 'float')]
    private float $complaintRate;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClient(): Client
    {
        return $this->client;
    }

    public function setClient($client): self
    {
        $this->client = $client;

        return $this;
    }

    public function getTotalSent(): ?int
    {
        return $this->totalSent;
    }

    public function setTotalSent(int $totalSent): self
    {
        $this->totalSent = $totalSent;

        return $this;
    }

    public function getOpenRate(): ?float
    {
        return $this->openRate;
    }

    public function setOpenRate(float $openRate): self
    {
        $this->openRate = $openRate;

        return $this;
    }

    public function getUnsubscriptionRate(): ?float
    {
        return $this->unsubscriptionRate;
    }

    public function setUnsubscriptionRate(float $unsubscriptionRate): self
    {
        $this->unsubscriptionRate = $unsubscriptionRate;

        return $this;
    }

    public function getBounceRate(): ?float
    {
        return $this->bounceRate;
    }

    public function setBounceRate(float $bounceRate): self
    {
        $this->bounceRate = $bounceRate;

        return $this;
    }

    public function getComplaintRate(): ?float
    {
        return $this->complaintRate;
    }

    public function setComplaintRate(float $complaintRate): self
    {
        $this->complaintRate = $complaintRate;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'client' => $this->getClient(),
            'totalSent' => $this->getTotalSent(),
            'openRate' => $this->getOpenRate(),
            'unsubscriptionRate' => $this->getUnsubscriptionRate(),
            'bounceRate' => $this->getBounceRate(),
            'complaintRate' => $this->getComplaintRate(),
        ];
    }

}