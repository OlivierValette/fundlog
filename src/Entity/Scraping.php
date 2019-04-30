<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Scraping
 *
 * @ORM\Table(name="scraping", indexes={@ORM\Index(name="fk_scraping_source1_idx", columns={"source_id"})})
 * @ORM\Entity
 */
class Scraping
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
     * @ORM\Column(name="item", type="string", length=255, nullable=false)
     */
    private $item;

    /**
     * @var string|null
     *
     * @ORM\Column(name="tag0", type="string", length=255, nullable=true)
     */
    private $tag0;

    /**
     * @var string|null
     *
     * @ORM\Column(name="tag1", type="string", length=255, nullable=true)
     */
    private $tag1;

    /**
     * @var string|null
     *
     * @ORM\Column(name="tag2", type="string", length=255, nullable=true)
     */
    private $tag2;

    /**
     * @var string|null
     *
     * @ORM\Column(name="class0", type="string", length=255, nullable=true)
     */
    private $class0;

    /**
     * @var string|null
     *
     * @ORM\Column(name="class1", type="string", length=255, nullable=true)
     */
    private $class1;

    /**
     * @var string|null
     *
     * @ORM\Column(name="class2", type="string", length=255, nullable=true)
     */
    private $class2;

    /**
     * @var int|null
     *
     * @ORM\Column(name="index0", type="integer", nullable=true)
     */
    private $index0;

    /**
     * @var int|null
     *
     * @ORM\Column(name="index1", type="integer", nullable=true)
     */
    private $index1;

    /**
     * @var int|null
     *
     * @ORM\Column(name="index2", type="integer", nullable=true)
     */
    private $index2;

    /**
     * @var string|null
     *
     * @ORM\Column(name="attr0", type="string", length=255, nullable=true)
     */
    private $attr0;

    /**
     * @var string|null
     *
     * @ORM\Column(name="attr1", type="string", length=255, nullable=true)
     */
    private $attr1;

    /**
     * @var string|null
     *
     * @ORM\Column(name="attr2", type="string", length=255, nullable=true)
     */
    private $attr2;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="stringify", type="boolean", nullable=true)
     */
    private $stringify;

    /**
     * @var string|null
     *
     * @ORM\Column(name="moreover", type="string", length=255, nullable=true)
     */
    private $moreover;

    /**
     * @var \Source
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

    public function getItem(): ?string
    {
        return $this->item;
    }

    public function setItem(string $item): self
    {
        $this->item = $item;

        return $this;
    }

    public function getTag0(): ?string
    {
        return $this->tag0;
    }

    public function setTag0(?string $tag0): self
    {
        $this->tag0 = $tag0;

        return $this;
    }

    public function getTag1(): ?string
    {
        return $this->tag1;
    }

    public function setTag1(?string $tag1): self
    {
        $this->tag1 = $tag1;

        return $this;
    }

    public function getTag2(): ?string
    {
        return $this->tag2;
    }

    public function setTag2(?string $tag2): self
    {
        $this->tag2 = $tag2;

        return $this;
    }

    public function getClass0(): ?string
    {
        return $this->class0;
    }

    public function setClass0(?string $class0): self
    {
        $this->class0 = $class0;

        return $this;
    }

    public function getClass1(): ?string
    {
        return $this->class1;
    }

    public function setClass1(?string $class1): self
    {
        $this->class1 = $class1;

        return $this;
    }

    public function getClass2(): ?string
    {
        return $this->class2;
    }

    public function setClass2(?string $class2): self
    {
        $this->class2 = $class2;

        return $this;
    }

    public function getIndex0(): ?int
    {
        return $this->index0;
    }

    public function setIndex0(?int $index0): self
    {
        $this->index0 = $index0;

        return $this;
    }

    public function getIndex1(): ?int
    {
        return $this->index1;
    }

    public function setIndex1(?int $index1): self
    {
        $this->index1 = $index1;

        return $this;
    }

    public function getIndex2(): ?int
    {
        return $this->index2;
    }

    public function setIndex2(?int $index2): self
    {
        $this->index2 = $index2;

        return $this;
    }

    public function getAttr0(): ?string
    {
        return $this->attr0;
    }

    public function setAttr0(?string $attr0): self
    {
        $this->attr0 = $attr0;

        return $this;
    }

    public function getAttr1(): ?string
    {
        return $this->attr1;
    }

    public function setAttr1(?string $attr1): self
    {
        $this->attr1 = $attr1;

        return $this;
    }

    public function getAttr2(): ?string
    {
        return $this->attr2;
    }

    public function setAttr2(?string $attr2): self
    {
        $this->attr2 = $attr2;

        return $this;
    }

    public function getStringify(): ?bool
    {
        return $this->stringify;
    }

    public function setStringify(?bool $stringify): self
    {
        $this->stringify = $stringify;

        return $this;
    }

    public function getMoreover(): ?string
    {
        return $this->moreover;
    }

    public function setMoreover(?string $moreover): self
    {
        $this->moreover = $moreover;

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
    
    public function __toString()
    {
        return $this->getSource()->getName() + $this->getItem();
    }
    
}
