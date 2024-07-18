<?php

namespace App\Entity;

use App\Repository\ClientStatsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClientStatsRepository::class)]
#[ORM\Table(name: 'client_stats')]

class ClientStats
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'integer')]
    private $clientId;

    #[ORM\Column(type: 'integer')]
    private $totalSent;

    #[ORM\Column(type: 'float')]
    private $openRate;

    #[ORM\Column(type: 'float')]
    private $unsubscriptionRate;

    #[ORM\Column(type: 'float')]
    private $bounceRate;

    #[ORM\Column(type: 'float')]
    private $complaintRate;

    public function getId(): ?int
    {
        return $this->id;
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
            'clientId' => $this->getClientId(),
            'totalSent' => $this->getTotalSent(),
            'openRate' => $this->getOpenRate(),
            'unsubscriptionRate' => $this->getUnsubscriptionRate(),
            'bounceRate' => $this->getBounceRate(),
            'complaintRate' => $this->getComplaintRate(),
        ];
    }

}