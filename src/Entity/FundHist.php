<?php

namespace App\Entity;

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
     * @var int
     *
     * @ORM\Column(name="year", type="bigint", nullable=false)
     */
    private $year;

    /**
     * @var float|null
     *
     * @ORM\Column(name="perf", type="float", precision=10, scale=0, nullable=true)
     */
    private $perf;

    /**
     * @var \Fund
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

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(int $year): self
    {
        $this->year = $year;

        return $this;
    }

    public function getPerf(): ?float
    {
        return $this->perf;
    }

    public function setPerf(?float $perf): self
    {
        $this->perf = $perf;

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


}
