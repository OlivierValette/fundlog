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
     * @ORM\Column(name="tag", type="string", length=255, nullable=true)
     */
    private $tag;

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
     * @ORM\Column(name="class", type="string", length=255, nullable=true)
     */
    private $class;

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
     * @ORM\Column(name="index", type="integer", nullable=true)
     */
    private $index;

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
     * @ORM\Column(name="attr", type="string", length=255, nullable=true)
     */
    private $attr;

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
     * @ORM\Column(name="else", type="string", length=255, nullable=true)
     */
    private $else;

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

    public function getTag(): ?string
    {
        return $this->tag;
    }

    public function setTag(?string $tag): self
    {
        $this->tag = $tag;

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

    public function getClass(): ?string
    {
        return $this->class;
    }

    public function setClass(?string $class): self
    {
        $this->class = $class;

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

    public function getIndex(): ?int
    {
        return $this->index;
    }

    public function setIndex(?int $index): self
    {
        $this->index = $index;

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

    public function getAttr(): ?string
    {
        return $this->attr;
    }

    public function setAttr(?string $attr): self
    {
        $this->attr = $attr;

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

    public function getElse(): ?string
    {
        return $this->else;
    }

    public function setElse(?string $else): self
    {
        $this->else = $else;

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
