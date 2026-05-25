<?php

namespace App\Model;

class Cart
{
    public const DEFAULT_IDENTIFIER = 'main';

    private string $id;

    private \DateTimeImmutable $createdAt;

    /** @var CartItem[] */
    private array $cartItems = [];

    public function __construct(string $id = self::DEFAULT_IDENTIFIER)
    {
        $this->id = $id;
        $this->createdAt = new \DateTimeImmutable();
    }

    public static function fromArray(array $data): self
    {
        $cart = new self($data['id'] ?? self::DEFAULT_IDENTIFIER);
        $cart->createdAt = new \DateTimeImmutable($data['createdAt'] ?? 'now');

        foreach ($data['cartItems'] ?? [] as $itemData) {
            $cart->cartItems[] = CartItem::fromArray($itemData);
        }

        return $cart;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @return CartItem[]
     */
    public function getCartItems(): array
    {
        return $this->cartItems;
    }

    public function addItem(CartItem $item): void
    {
        foreach ($this->cartItems as $existingItem) {
            if ($existingItem->getProductId() === $item->getProductId()) {
                $existingItem->setQuantity($existingItem->getQuantity() + $item->getQuantity());

                return;
            }
        }

        $this->cartItems[] = $item;
    }

    public function removeItem(CartItem $item): void
    {
        $this->cartItems = array_values(array_filter(
            $this->cartItems,
            fn (CartItem $cartItem) => $cartItem->getProductId() !== $item->getProductId()
        ));
    }

    public function findItemByProductId(int $productId): ?CartItem
    {
        foreach ($this->cartItems as $cartItem) {
            if ($cartItem->getProductId() === $productId) {
                return $cartItem;
            }
        }

        return null;
    }

    public function total(): float
    {
        $total = 0.0;

        foreach ($this->cartItems as $item) {
            $total += $item->getLineTotal();
        }

        return $total;
    }

    public function isEmpty(): bool
    {
        return [] === $this->cartItems;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'createdAt' => $this->createdAt->format(\DateTimeInterface::ATOM),
            'cartItems' => array_map(
                fn (CartItem $item) => $item->toArray(),
                $this->cartItems
            ),
        ];
    }
}
