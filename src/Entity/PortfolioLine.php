<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PortfolioLine
 *
 * @ORM\Table(name="portfolio_line", indexes={@ORM\Index(name="fk_portfolio_line_fund1_idx", columns={"fund_id"}), @ORM\Index(name="fk_portfolio_line_portfolio1_idx", columns={"portfolio_id"})})
 * @ORM\Entity
 */
class PortfolioLine
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
     * @var float
     *
     * @ORM\Column(name="qty", type="float", precision=10, scale=0, nullable=false)
     */
    private $qty;

    /**
     * @var float|null
     *
     * @ORM\Column(name="lvalue", type="float", precision=10, scale=0, nullable=true)
     */
    private $lvalue;

    /**
     * @var float|null
     *
     * @ORM\Column(name="io_qty", type="float", precision=10, scale=0, nullable=true)
     */
    private $ioQty;

    /**
     * @var float|null
     *
     * @ORM\Column(name="io_value", type="float", precision=10, scale=0, nullable=true)
     */
    private $ioValue;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="io_hide", type="boolean", nullable=true)
     */
    private $ioHide = '0';

    /**
     * @var \Fund
     *
     * @ORM\ManyToOne(targetEntity="Fund")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="fund_id", referencedColumnName="id")
     * })
     */
    private $fund;

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

    public function getQty(): ?float
    {
        return $this->qty;
    }

    public function setQty(float $qty): self
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

    public function getIoQty(): ?float
    {
        return $this->ioQty;
    }

    public function setIoQty(?float $ioQty): self
    {
        $this->ioQty = $ioQty;

        return $this;
    }

    public function getIoValue(): ?float
    {
        return $this->ioValue;
    }

    public function setIoValue(?float $ioValue): self
    {
        $this->ioValue = $ioValue;

        return $this;
    }

    public function getIoHide(): ?bool
    {
        return $this->ioHide;
    }

    public function setIoHide(?bool $ioHide): self
    {
        $this->ioHide = $ioHide;

        return $this;
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
