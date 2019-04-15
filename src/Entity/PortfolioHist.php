<?php

namespace App\Entity;

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
     * @var int
     *
     * @ORM\Column(name="pfh_year", type="bigint", nullable=false)
     */
    private $pfhYear;

    /**
     * @var float|null
     *
     * @ORM\Column(name="pfh_lvalue", type="float", precision=10, scale=0, nullable=true)
     */
    private $pfhLvalue;

    /**
     * @var float|null
     *
     * @ORM\Column(name="pfh_perf", type="float", precision=10, scale=0, nullable=true)
     */
    private $pfhPerf;

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

    public function getPfhYear(): ?int
    {
        return $this->pfhYear;
    }

    public function setPfhYear(int $pfhYear): self
    {
        $this->pfhYear = $pfhYear;

        return $this;
    }

    public function getPfhLvalue(): ?float
    {
        return $this->pfhLvalue;
    }

    public function setPfhLvalue(?float $pfhLvalue): self
    {
        $this->pfhLvalue = $pfhLvalue;

        return $this;
    }

    public function getPfhPerf(): ?float
    {
        return $this->pfhPerf;
    }

    public function setPfhPerf(?float $pfhPerf): self
    {
        $this->pfhPerf = $pfhPerf;

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
