<?php

namespace App\Model;

use App\Entity\Product;

class CartItem
{
    private ?int $id = null;

    private int $productId;

    private string $productName;

    private float $price;

    private int $quantity;

    public static function fromProduct(Product $product, int $quantity): self
    {
        $item = new self();
        $item->id = $product->getId();
        $item->productId = $product->getId();
        $item->productName = $product->getName();
        $item->price = (float) $product->getPrice();
        $item->quantity = $quantity;

        return $item;
    }

    public static function fromArray(array $data): self
    {
        $item = new self();
        $item->id = $data['id'] ?? $data['productId'];
        $item->productId = $data['productId'];
        $item->productName = $data['productName'];
        $item->price = (float) $data['price'];
        $item->quantity = (int) $data['quantity'];

        return $item;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProductId(): int
    {
        return $this->productId;
    }

    public function getProductName(): string
    {
        return $this->productName;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }

    public function getLineTotal(): float
    {
        return $this->price * $this->quantity;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'productId' => $this->productId,
            'productName' => $this->productName,
            'price' => $this->price,
            'quantity' => $this->quantity,
        ];
    }
}
