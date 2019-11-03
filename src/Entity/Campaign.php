<?php

namespace App\Entity;

use App\Entity\Traits\NameTrait;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CampaignRepository")
 * @Gedmo\Loggable()
 */
class Campaign
{
    use NameTrait;

    public const TYPE_STATIC = 1;
    public const TYPE_PERCENT = 2;

    public const TYPE_DESC = [
        self::TYPE_STATIC => 'Sabit İndirim',
        self::TYPE_PERCENT => 'Yüzdelik İndirim'
    ];

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Brand" , inversedBy="campaigns")
     * @Gedmo\Versioned()
     */
    private $brand;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="campaigns")
     * @Gedmo\Versioned()
     */
    private $category;

    /**
     * @var float
     * @ORM\Column(name="amount", type="float")
     * @Gedmo\Versioned()
     */
    private $amount;

    /**
     * @var int
     * @ORM\Column(name="type", type="integer")
     * @Gedmo\Versioned()
     */
    private $type;

    /**
     * @var DateTime
     * @ORM\Column(name="start_date", type="date")
     * @Gedmo\Versioned()
     */
    private $startDate;

    /**
     * @var DateTime
     * @ORM\Column(name="end_date", type="date")
     * @Gedmo\Versioned()
     */
    private $endDate;

    /**
     * @var int
     * @ORM\Column(name="priority", type="integer")
     * @Gedmo\Versioned()
     */
    private $priority;

    /**
     * @var ArrayCollection|Product[]
     * @ORM\ManyToMany(targetEntity="App\Entity\Product", mappedBy="relatedCampaigns")
     */
    private $relatedProducts;

    /**
     * Campaign constructor.
     */
    public function __construct()
    {
        $this->relatedProducts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
     */
    public function setBrand($brand): void
    {
        $this->brand = $brand;
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
     */
    public function setCategory($category): void
    {
        $this->category = $category;
    }

    /**
     * @return float|null
     */
    public function getAmount(): ?float
    {
        return $this->amount;
    }

    /**
     * @param float $amount
     */
    public function setAmount(float $amount): void
    {
        $this->amount = $amount;
    }

    /**
     * @return int|null
     */
    public function getType(): ?int
    {
        return $this->type;
    }

    /**
     * @return string|null
     */
    public function getTypeDesc(): ?string
    {
        return self::TYPE_DESC[$this->type];
    }

    /**
     * @param int $type
     */
    public function setType(int $type): void
    {
        $this->type = $type;
    }

    /**
     * @return DateTime|null
     */
    public function getStartDate(): ?DateTime
    {
        return $this->startDate;
    }

    /**
     * @param DateTime $startDate
     */
    public function setStartDate(DateTime $startDate): void
    {
        $this->startDate = $startDate;
    }

    /**
     * @return DateTime|null
     */
    public function getEndDate(): ?DateTime
    {
        return $this->endDate;
    }

    /**
     * @param DateTime $endDate
     */
    public function setEndDate(DateTime $endDate): void
    {
        $this->endDate = $endDate;
    }

    /**
     * @return int|null
     */
    public function getPriority(): ?int
    {
        return $this->priority;
    }

    /**
     * @param int $priority
     */
    public function setPriority(int $priority): void
    {
        $this->priority = $priority;
    }

    /**
     * @return Product[]|ArrayCollection
     */
    public function getRelatedProducts()
    {
        return $this->relatedProducts;
    }

    /**
     * @param Product[]|ArrayCollection $relatedProducts
     */
    public function setRelatedProducts($relatedProducts): void
    {
        $this->relatedProducts = $relatedProducts;
    }
}
