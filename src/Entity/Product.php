<?php

namespace App\Entity;

use App\Entity\Traits\NameTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 */
class Product
{
    use NameTrait;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var float
     * @ORM\Column(name="price", type="float")
     */
    private $price;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Brand" , inversedBy="products")
     */
    private $brand;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="products")
     */
    private $category;

    /**
     * @var ArrayCollection|Campaign[]
     * @ORM\ManyToMany(targetEntity="App\Entity\Campaign", inversedBy="relatedProducts")
     */
    private $relatedCampaigns;

    /**
     * Product constructor.
     */
    public function __construct()
    {
        $this->relatedCampaigns = new ArrayCollection();
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
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * @param float $price
     * @return Product
     */
    public function setPrice(float $price): Product
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getBrand()
    {
        return $this->brand;
    }

    /**
     * @param mixed $brand
     * @return Product
     */
    public function setBrand($brand): Product
    {
        $this->brand = $brand;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param mixed $category
     * @return Product
     */
    public function setCategory($category): Product
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Campaign[]|ArrayCollection
     */
    public function getRelatedCampaigns()
    {
        return $this->relatedCampaigns;
    }

    /**
     * @param Campaign[]|ArrayCollection $relatedCampaigns
     * @return Product
     */
    public function setRelatedCampaigns($relatedCampaigns): Product
    {
        $this->relatedCampaigns = $relatedCampaigns;

        return $this;
    }

    /**
     * @param Campaign $campaign
     * @return $this
     */
    public function addRelatedCampaign(Campaign $campaign): self
    {
        if ($this->relatedCampaigns->contains($campaign) === false){
            $this->relatedCampaigns->add($campaign);
        }

        return $this;
    }
}
