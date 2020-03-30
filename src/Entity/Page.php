<?php

namespace MonsieurBiz\SyliusRichEditorPlugin\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="MonsieurBiz\SyliusRichEditorPlugin\Repository\PageRepository")
 */
class Page
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $blocks;

    /**
     * @ORM\Column(type="text")
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     */
    private $slug;

    /**
     * @ORM\ManyToOne(targetEntity="MonsieurBiz\SyliusRichEditorPlugin\Entity\Page", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", nullable=true)
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity="MonsieurBiz\SyliusRichEditorPlugin\Entity\Page", mappedBy="parent")
     */
    private $children;

    public function __construct()
    {
        $this->children = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBlocks(): ?string
    {
        return $this->blocks;
    }

    public function setBlocks(?string $blocks): self
    {
        $this->blocks = $blocks;

        return $this;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title): void
    {
        $this->title = $title;
        $this->setSlug();
    }

    public function getSlug()
    {
        return $this->slug;
    }

    public function setSlug(): void
    {
        if(empty($this->parent)){
            $this->slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $this->title)));
        }else{
            $this->slug = $this->parent->getSlug(). '/'. strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $this->title)));
        }
    }

    public function slugDisplay()
    {
        return '/' . $this->slug;
    }

    public function getParent()
    {
        return $this->parent;
    }

    public function setParent($parent): void
    {
        $this->parent = $parent;
    }

    public function getChildren()
    {
        return $this->children;
    }

}
