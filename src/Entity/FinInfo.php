<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * FinInfo
 *
 * @ORM\Table(name="fin_info", indexes={@ORM\Index(name="fk_fin_info_fund1_idx", columns={"fund_id"}), @ORM\Index(name="fk_fin_info_source1_idx", columns={"source_id"})})
 * @ORM\Entity
 */
class FinInfo
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
     * @ORM\Column(name="isin", type="string", length=16, nullable=false)
     */
    private $isin;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=16, nullable=false)
     */
    private $code;

    /**
     * @var string|null
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="rating", type="boolean", nullable=true)
     */
    private $rating;

    /**
     * @var string|null
     *
     * @ORM\Column(name="benchmark", type="string", length=255, nullable=true)
     */
    private $benchmark;

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
     * @var DateTime|null
     *
     * @ORM\Column(name="date_ytd", type="datetime", nullable=true)
     */
    private $dateYtd;

    /**
     * @var float|null
     *
     * @ORM\Column(name="perf_a", type="float", precision=10, scale=0, nullable=true)
     */
    private $perfA;

    /**
     * @var float|null
     *
     * @ORM\Column(name="perf_am1", type="float", precision=10, scale=0, nullable=true)
     */
    private $perfAm1;

    /**
     * @var float|null
     *
     * @ORM\Column(name="perf_am2", type="float", precision=10, scale=0, nullable=true)
     */
    private $perfAm2;

    /**
     * @var float|null
     *
     * @ORM\Column(name="perf_am3", type="float", precision=10, scale=0, nullable=true)
     */
    private $perfAm3;
    
    /**
     * @var Currency
     *
     * @ORM\ManyToOne(targetEntity="Currency")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="currency_id", referencedColumnName="id")
     * })
     */
    private $currency;

    /**
     * @var Fund
     *
     * @ORM\ManyToOne(targetEntity="Fund")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="fund_id", referencedColumnName="id")
     * })
     */
    private $fund;

    /**
     * @var Source
     *
     * @ORM\ManyToOne(targetEntity="Source")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="source_id", referencedColumnName="id")
     * })
     */
    private $source;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIsin(): ?string
    {
        return $this->isin;
    }

    public function setIsin(string $isin): self
    {
        $this->isin = $isin;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getRating(): ?bool
    {
        return $this->rating;
    }

    public function setRating(?bool $rating): self
    {
        $this->rating = $rating;

        return $this;
    }

    public function getBenchmark(): ?string
    {
        return $this->benchmark;
    }

    public function setBenchmark(?string $benchmark): self
    {
        $this->benchmark = $benchmark;

        return $this;
    }

    public function getLvdate(): ?\DateTimeInterface
    {
        return $this->lvdate;
    }

    public function setLvdate(?\DateTimeInterface $lvdate): self
    {
        $this->lvdate = $lvdate;

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

    public function getDateYtd(): ?\DateTimeInterface
    {
        return $this->dateYtd;
    }

    public function setDateYtd(?\DateTimeInterface $dateYtd): self
    {
        $this->dateYtd = $dateYtd;

        return $this;
    }

    public function getPerfA(): ?float
    {
        return $this->perfA;
    }

    public function setPerfA(?float $perfA): self
    {
        $this->perfA = $perfA;

        return $this;
    }

    public function getPerfAm1(): ?float
    {
        return $this->perfAm1;
    }

    public function setPerfAm1(?float $perfAm1): self
    {
        $this->perfAm1 = $perfAm1;

        return $this;
    }

    public function getPerfAm2(): ?float
    {
        return $this->perfAm2;
    }

    public function setPerfAm2(?float $perfAm2): self
    {
        $this->perfAm2 = $perfAm2;

        return $this;
    }

    public function getPerfAm3(): ?float
    {
        return $this->perfAm3;
    }

    public function setPerfAm3(?float $perfAm3): self
    {
        $this->perfAm3 = $perfAm3;

        return $this;
    }
    
    public function getCurrency(): ?Currency
    {
        return $this->currency;
    }
    
    public function setCurrency(?Currency $currency): self
    {
        $this->currency = $currency;
        
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

    public function getSource(): ?Source
    {
        return $this->source;
    }

    public function setSource(?Source $source): self
    {
        $this->source = $source;

        return $this;
    }

}
