<?php

namespace App\Entity;

use App\Entity\Traits\NameTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CategoryRepository")
 */
class Category
{
    use NameTrait;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var ArrayCollection|Product[]
     * @ORM\OneToMany(targetEntity="App\Entity\Product", mappedBy="category")
     */
    private $products;

    /**
     * @var ArrayCollection|Campaign[]
     * @ORM\OneToMany(targetEntity="App\Entity\Product", mappedBy="category")
     */
    private $campaigns;

    /**
     * Brand constructor.
     */
    public function __construct()
    {
        $this->products = new ArrayCollection();
        $this->campaigns = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getName();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Product[]|ArrayCollection
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * @param Product[]|ArrayCollection $products
     */
    public function setProducts($products): void
    {
        $this->products = $products;
    }

    /**
     * @return Campaign[]|ArrayCollection
     */
    public function getCampaigns()
    {
        return $this->campaigns;
    }

    /**
     * @param Campaign[]|ArrayCollection $campaigns
     */
    public function setCampaigns($campaigns): void
    {
        $this->campaigns = $campaigns;
    }
}
