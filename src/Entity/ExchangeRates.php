<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ExchangeRatesRepository")
 */
class ExchangeRates
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $currency;

    /**
     * @ORM\Column(type="float")
     */
    private $exchange_rate;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $created_datetime;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $updated_datetime;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): self
    {
        $this->currency = $currency;

        return $this;
    }

    public function getExchangeRate(): ?float
    {
        return $this->exchange_rate;
    }

    public function setExchangeRate(float $exchange_rate): self
    {
        $this->exchange_rate = $exchange_rate;

        return $this;
    }

    public function getCreatedDatetime(): ?\DateTimeInterface
    {
        return $this->created_datetime;
    }

    public function setCreatedDatetime(\DateTimeInterface $created_datetime): self
    {
        $this->created_datetime = $created_datetime;

        return $this;
    }

    public function getUpdatedDatetime(): ?\DateTimeInterface
    {
        return $this->updated_datetime;
    }

    public function setUpdatedDatetime(\DateTimeInterface $updated_datetime): self
    {
        $this->updated_datetime = $updated_datetime;

        return $this;
    }
}
