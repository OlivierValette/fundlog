<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Source
 *
 * @ORM\Table(name="source")
 * @ORM\Entity
 */
class Source
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="search_url", type="string", length=255, nullable=false)
     */
    private $search_url;
    
    /**
     * @var string
     *
     * @ORM\Column(name="fund_url", type="string", length=255, nullable=false)
     */
    private $fund_url;
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSearchUrl(): ?string
    {
        return $this->search_url;
    }

    public function setSearchUrl(string $search_url): self
    {
        $this->search_url = $search_url;

        return $this;
    }

    public function getFundUrl(): ?string
    {
        return $this->fund_url;
    }

    public function setFundUrl(string $fund_url): self
    {
        $this->fund_url = $fund_url;

        return $this;
    }


}
