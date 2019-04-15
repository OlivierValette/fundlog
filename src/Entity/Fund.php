<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Fund
 *
 * @ORM\Table(name="fund", uniqueConstraints={@ORM\UniqueConstraint(name="id_UNIQUE", columns={"id"}), @ORM\UniqueConstraint(name="isin_UNIQUE", columns={"isin"})}, indexes={@ORM\Index(name="fk_fund_asset_class1_idx", columns={"asset_class_id"}), @ORM\Index(name="fk_fund_category1_idx", columns={"category_category_id"})})
 * @ORM\Entity
 */
class Fund
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
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var float|null
     *
     * @ORM\Column(name="last_lvalue", type="float", precision=10, scale=0, nullable=true)
     */
    private $lastLvalue;

    /**
     * @var \AssetClass
     *
     * @ORM\ManyToOne(targetEntity="AssetClass")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="asset_class_id", referencedColumnName="id")
     * })
     */
    private $assetClass;

    /**
     * @var \Category
     *
     * @ORM\ManyToOne(targetEntity="Category")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="category_category_id", referencedColumnName="category_id")
     * })
     */
    private $categoryCategory;

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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getLastLvalue(): ?float
    {
        return $this->lastLvalue;
    }

    public function setLastLvalue(?float $lastLvalue): self
    {
        $this->lastLvalue = $lastLvalue;

        return $this;
    }

    public function getAssetClass(): ?AssetClass
    {
        return $this->assetClass;
    }

    public function setAssetClass(?AssetClass $assetClass): self
    {
        $this->assetClass = $assetClass;

        return $this;
    }

    public function getCategoryCategory(): ?Category
    {
        return $this->categoryCategory;
    }

    public function setCategoryCategory(?Category $categoryCategory): self
    {
        $this->categoryCategory = $categoryCategory;

        return $this;
    }


}
