<?php

namespace App\Cart;

use App\Model\Cart;
use App\Model\CartItem;

interface CartInterface
{
    public function add(CartItem $item, Cart $cart): Cart;

    public function remove(CartItem $item, Cart $cart): Cart;

    public function getCart(string $identifier): Cart;

    public function clearCart(string $identifier): void;
}
