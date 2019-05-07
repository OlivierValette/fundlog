<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * PortfolioHist
 *
 * @ORM\Table(name="portfolio_hist", indexes={@ORM\Index(name="fk_portfolio_hist_portfolio1_idx", columns={"portfolio_id"})})
 * @ORM\Entity
 */
class PortfolioHist
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
     * @var \Portfolio
     *
     * @ORM\ManyToOne(targetEntity="Portfolio")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="portfolio_id", referencedColumnName="id")
     * })
     */
    private $portfolio;

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

    public function getPortfolio(): ?Portfolio
    {
        return $this->portfolio;
    }

    public function setPortfolio(?Portfolio $portfolio): self
    {
        $this->portfolio = $portfolio;

        return $this;
    }


}
