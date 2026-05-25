<?php

namespace App\Cart;

use App\Model\Cart;
use App\Model\CartItem;
use Symfony\Component\HttpFoundation\RequestStack;

class SessionCart implements CartInterface
{
    private const SESSION_KEY_PREFIX = 'cart_';

    public function __construct(
        private readonly RequestStack $requestStack,
    ) {
    }

    public function add(CartItem $item, Cart $cart): Cart
    {
        $cart->addItem($item);
        $this->saveCart($cart);

        return $cart;
    }

    public function remove(CartItem $item, Cart $cart): Cart
    {
        $cart->removeItem($item);
        $this->saveCart($cart);

        return $cart;
    }

    public function getCart(string $identifier): Cart
    {
        $session = $this->requestStack->getSession();
        $data = $session->get($this->getSessionKey($identifier));

        if (!\is_array($data)) {
            return new Cart($identifier);
        }

        return Cart::fromArray($data);
    }

    public function clearCart(string $identifier): void
    {
        $this->requestStack->getSession()->remove($this->getSessionKey($identifier));
    }

    private function saveCart(Cart $cart): void
    {
        $this->requestStack->getSession()->set(
            $this->getSessionKey($cart->getId()),
            $cart->toArray()
        );
    }

    private function getSessionKey(string $identifier): string
    {
        return self::SESSION_KEY_PREFIX.$identifier;
    }
}
