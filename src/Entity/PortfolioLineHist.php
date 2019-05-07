<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * PortfolioLineHist
 *
 * @ORM\Table(name="portfolio_line_hist", indexes={@ORM\Index(name="fk_portfolio_line_hist_portfolio_line1_idx", columns={"portfolio_line_id"})})
 * @ORM\Entity
 */
class PortfolioLineHist
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
     * @var float|null
     *
     * @ORM\Column(name="qty", type="float", precision=10, scale=0, nullable=true)
     */
    private $qty;
    
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
     * @var \PortfolioLine
     *
     * @ORM\ManyToOne(targetEntity="PortfolioLine")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="portfolio_line_id", referencedColumnName="id")
     * })
     */
    private $portfolioLine;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQty(): ?float
    {
        return $this->qty;
    }

    public function setQty(?float $qty): self
    {
        $this->qty = $qty;

        return $this;
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
    

    public function getLvalue(): ?float
    {
        return $this->lvalue;
    }

    public function setLvalue(?float $lvalue): self
    {
        $this->lvalue = $lvalue;

        return $this;
    }

    public function getPortfolioLine(): ?PortfolioLine
    {
        return $this->portfolioLine;
    }

    public function setPortfolioLine(?PortfolioLine $portfolioLine): self
    {
        $this->portfolioLine = $portfolioLine;

        return $this;
    }

}
