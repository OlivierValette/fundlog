<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Portfolio
 *
 * @ORM\Table(
 *     name="portfolio",
 *     indexes={@ORM\Index(name="fk_portfolio_middleman1_idx", columns={"middleman_id"}), @ORM\Index(name="fk_portfolio_user_idx", columns={"user_id"}), @ORM\Index(name="fk_portfolio_lifeinsurance1_idx", columns={"lifeinsurance_id"})})
 * @ORM\Entity
 */
class Portfolio
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
     * @var \DateTime|null
     *
     * @ORM\Column(name="create_date", type="datetime", nullable=true)
     */
    private $createDate;

    /**
     * @var float|null
     *
     * @ORM\Column(name="inputs", type="float", precision=10, scale=0, nullable=true)
     */
    private $inputs;

    /**
     * @var float|null
     *
     * @ORM\Column(name="outputs", type="float", precision=10, scale=0, nullable=true)
     */
    private $outputs;

    /**
     * @var float|null
     *
     * @ORM\Column(name="last_total_amount", type="float", precision=10, scale=0, nullable=true)
     */
    private $lastTotalAmount;

    /**
     * @var float|null
     *
     * @ORM\Column(name="last_perf", type="float", precision=10, scale=0, nullable=true)
     */
    private $lastPerf;

    /**
     * @var bool
     *
     * @ORM\Column(name="archived", type="boolean", nullable=false)
     */
    private $archived = '0';

    /**
     * @var \Lifeinsurance
     *
     * @ORM\ManyToOne(targetEntity="Lifeinsurance")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="lifeinsurance_id", referencedColumnName="id")
     * })
     */
    private $lifeinsurance;

    /**
     * @var \Middleman
     *
     * @ORM\ManyToOne(targetEntity="Middleman")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="middleman_id", referencedColumnName="id")
     * })
     */
    private $middleman;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;

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

    public function getCreateDate(): ?\DateTimeInterface
    {
        return $this->createDate;
    }

    public function setCreateDate(?\DateTimeInterface $createDate): self
    {
        $this->createDate = $createDate;

        return $this;
    }

    public function getInputs(): ?float
    {
        return $this->inputs;
    }

    public function setInputs(?float $inputs): self
    {
        $this->inputs = $inputs;

        return $this;
    }

    public function getOutputs(): ?float
    {
        return $this->outputs;
    }

    public function setOutputs(?float $outputs): self
    {
        $this->outputs = $outputs;

        return $this;
    }

    public function getLastTotalAmount(): ?float
    {
        return $this->lastTotalAmount;
    }

    public function setLastTotalAmount(?float $lastTotalAmount): self
    {
        $this->lastTotalAmount = $lastTotalAmount;

        return $this;
    }

    public function getLastPerf(): ?float
    {
        return $this->lastPerf;
    }

    public function setLastPerf(?float $lastPerf): self
    {
        $this->lastPerf = $lastPerf;

        return $this;
    }

    public function getArchived(): ?bool
    {
        return $this->archived;
    }

    public function setArchived(bool $archived): self
    {
        $this->archived = $archived;

        return $this;
    }

    public function getLifeinsurance(): ?Lifeinsurance
    {
        return $this->lifeinsurance;
    }

    public function setLifeinsurance(?Lifeinsurance $lifeinsurance): self
    {
        $this->lifeinsurance = $lifeinsurance;

        return $this;
    }

    public function getMiddleman(): ?Middleman
    {
        return $this->middleman;
    }

    public function setMiddleman(?Middleman $middleman): self
    {
        $this->middleman = $middleman;

        return $this;
    }

    public function getUser(): ?UserDB
    {
        return $this->user;
    }

    public function setUser(?UserDB $user): self
    {
        $this->user = $user;

        return $this;
    }


}
