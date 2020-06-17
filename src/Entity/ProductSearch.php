<?php

namespace App\Entity;

class ProductSearch
{
    /**
     * @var string|null
     */
    private $reference;

    /**
     * @var object|null
     */
    private $productCategorie;

    /**
     * @var object|null
     */
    private $ops;

    /**
     * @var object|null
     */
    private $liness;

    /**
     * @return string|null
     */
    public function getReference(): ?string
    {
        return $this->reference;
    }

    /**
     * @param string|null $reference
     * @return ProductSearch
     */
    public function setReference(string $reference): ProductSearch
    {
        $this->reference = $reference;
        return $this;
    }

    /**
     * @return object|null
     */
    public function getProductCategorie(): ?object
    {
        return $this->productCategorie;
    }

    /**
     * @param object|null $productCategorie
     * @return ProductSearch
     */
    public function setProductCategorie(object $productCategorie): ProductSearch
    {
        $this->productCategorie = $productCategorie;
        return $this;
    }

    /**
     * @return object|null
     */
    public function getLiness(): ?object
    {
        return $this->liness;
    }

    /**
     * @param object|null $liness
     * @return ProductSearch
     */
    public function setLine(object $liness): ProductSearch
    {
        $this->liness = $liness;
        return $this;
    }

    /**
     * @return object|null
     */
    public function getOps(): ?object
    {
        return $this->ops;
    }

    /**
     * @param object|null $ops
     * @return ProductSearch
     */
    public function setOp(object $ops): ProductSearch
    {
        $this->ops = $ops;
        return $this;
    }
}
