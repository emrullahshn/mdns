<?php


namespace App\Entity\Traits;


use Doctrine\ORM\Mapping as ORM;

trait NameTrait
{
    /**
     * @var string
     * @ORM\Column(name="name", type="string")
     */
    private $name;

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return NameTrait
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
}
