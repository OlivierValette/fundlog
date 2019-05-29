<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * PortfolioIo
 *
 * @ORM\Table(name="portfolio_io", indexes={@ORM\Index(name="fk_portfolio_io_portfolio1_idx", columns={"portfolio_id"})})
 * @ORM\Entity(repositoryClass="App\Repository\PortfolioIoRepository")
 */
class PortfolioIo
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
     * @ORM\Column(name="net_amount", type="float", precision=10, scale=0, nullable=false)
     */
    private $netAmount;

    /**
     * @var DateTime|null
     *
     * @ORM\Column(name="creation_date", type="datetime", nullable=true)
     */
    private $creationDate;

    /**
     * @var DateTime|null
     *
     * @ORM\Column(name="update_date", type="datetime", nullable=true)
     */
    private $updateDate;

    /**
     * @var DateTime|null
     *
     * @ORM\Column(name="valid_date", type="datetime", nullable=true)
     */
    private $validDate;

    /**
     * @var DateTime|null
     *
     * @ORM\Column(name="send_date", type="datetime", nullable=true)
     */
    private $sendDate;

    /**
     * @var DateTime|null
     *
     * @ORM\Column(name="confirm_date", type="datetime", nullable=true)
     */
    private $confirmDate;

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

    public function getNetAmount(): ?float
    {
        return $this->netAmount;
    }

    public function setNetAmount(float $netAmount): self
    {
        $this->netAmount = $netAmount;

        return $this;
    }

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creationDate;
    }

    public function setCreationDate(?\DateTimeInterface $creationDate): self
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    public function getUpdateDate(): ?\DateTimeInterface
    {
        return $this->updateDate;
    }

    public function setUpdateDate(?\DateTimeInterface $updateDate): self
    {
        $this->updateDate = $updateDate;

        return $this;
    }

    public function getValidDate(): ?\DateTimeInterface
    {
        return $this->validDate;
    }

    public function setValidDate(?\DateTimeInterface $validDate): self
    {
        $this->validDate = $validDate;

        return $this;
    }

    public function getSendDate(): ?\DateTimeInterface
    {
        return $this->sendDate;
    }

    public function setSendDate(?\DateTimeInterface $sendDate): self
    {
        $this->sendDate = $sendDate;

        return $this;
    }

    public function getConfirmDate(): ?\DateTimeInterface
    {
        return $this->confirmDate;
    }

    public function setConfirmDate(?\DateTimeInterface $confirmDate): self
    {
        $this->confirmDate = $confirmDate;

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
