<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PfLineHist
 *
 * @ORM\Table(name="pf_line_hist", indexes={@ORM\Index(name="fk_pf_line_histo_portfolio_line1_idx", columns={"portfolio_line_id"})})
 * @ORM\Entity
 */
class PfLineHist
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
     * @var \DateTime|null
     *
     * @ORM\Column(name="io_date", type="datetime", nullable=true)
     */
    private $ioDate;

    /**
     * @var float|null
     *
     * @ORM\Column(name="qty", type="float", precision=10, scale=0, nullable=true)
     */
    private $qty;

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

    public function getIoDate(): ?\DateTimeInterface
    {
        return $this->ioDate;
    }

    public function setIoDate(?\DateTimeInterface $ioDate): self
    {
        $this->ioDate = $ioDate;

        return $this;
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
