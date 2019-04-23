<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Lifeinsurance
 *
 * @ORM\Table(name="lifeinsurance")
 * @ORM\Entity
 */
class Lifeinsurance
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
     * @ORM\Column(name="company_name", type="string", length=255, nullable=false)
     */
    private $companyName;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCompanyName(): ?string
    {
        return $this->companyName;
    }

    public function setCompanyName(string $companyName): self
    {
        $this->companyName = $companyName;

        return $this;
    }
    
    public function __toString()
    {
        return $this->getCompanyName();
    }
    
}
