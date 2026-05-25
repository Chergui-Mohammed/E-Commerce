<?php

namespace App\Cart;

use App\Model\Cart;
use App\Model\CartItem;

class CartHandler
{
    public function handle(Cart $cart, CartInterface $strategy): Cart
    {
        $identifier = $cart->getId();
        $strategy->clearCart($identifier);

        $currentCart = $strategy->getCart($identifier);

        foreach ($cart->getCartItems() as $item) {
            $currentCart = $strategy->add($item, $currentCart);
        }

        return $currentCart;
    }

    public function addItem(CartItem $item, CartInterface $strategy, string $identifier = Cart::DEFAULT_IDENTIFIER): Cart
    {
        $cart = $strategy->getCart($identifier);

        return $strategy->add($item, $cart);
    }

    public function removeItem(int $productId, CartInterface $strategy, string $identifier = Cart::DEFAULT_IDENTIFIER): Cart
    {
        $cart = $strategy->getCart($identifier);
        $item = $cart->findItemByProductId($productId);

        if (null === $item) {
            return $cart;
        }

        return $strategy->remove($item, $cart);
    }

    public function getCart(CartInterface $strategy, string $identifier = Cart::DEFAULT_IDENTIFIER): Cart
    {
        return $strategy->getCart($identifier);
    }
}
