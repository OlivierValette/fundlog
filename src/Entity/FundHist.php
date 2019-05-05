<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * FundHist
 *
 * @ORM\Table(name="fund_hist", indexes={@ORM\Index(name="fk_historical_values_fund1_idx", columns={"fund_id"})})
 * @ORM\Entity
 */
class FundHist
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
     * @var DateTime|null
     *
     * @ORM\Column(name="lvdate", type="datetime", nullable=true)
     */
    private $lvdate;
    
    /**
     * @var float|null
     *
     * @ORM\Column(name="lvalue", type="float", precision=10, scale=0, nullable=true)
     */
    private $lvalue;

    /**
     * @var Fund
     *
     * @ORM\ManyToOne(targetEntity="Fund")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="fund_id", referencedColumnName="id")
     * })
     */
    private $fund;

    public function getId(): ?int
    {
        return $this->id;
    }
    
    /**
     * @return DateTime|null
     */
    public function getLvdate(): ?DateTime
    {
        return $this->lvdate;
    }
    
    /**
     * @param DateTime|null $lvdate
     */
    public function setLvdate(?DateTime $lvdate): void
    {
        $this->lvdate = $lvdate;
    }
    
    /**
     * @return float|null
     */
    public function getLvalue(): ?float
    {
        return $this->lvalue;
    }
    
    /**
     * @param float|null $lvalue
     */
    public function setLvalue(?float $lvalue): void
    {
        $this->lvalue = $lvalue;
    }

    public function getFund(): ?Fund
    {
        return $this->fund;
    }

    public function setFund(?Fund $fund): self
    {
        $this->fund = $fund;

        return $this;
    }


}
