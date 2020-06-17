<?php

namespace App\Service\Cart;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartService
{
    protected $session;

    protected $productRepository;

    public function __construct(SessionInterface $session, ProductRepository $productRepository)
    {
        $this->session = $session;
        $this->productRepository = $productRepository;
    }

    /**
     * @param integer $id
     * @return void
     */
    public function add(int $id, int $quantity)
    {
        $order = $this->session->get('order', []);

        if (!empty($order[$id])) {
            $order[$id] += $quantity;
        } else {
            $order[$id] = $quantity;
        }

        $this->session->set('order', $order);
    }

    /**
     * @param integer $id
     * @return void
     */
    public function remove(int $id)
    {
        $order = $this->session->get('order', []);

        if (!empty($order[$id])) {
            unset($order[$id]);
        }

        $this->session->set('order', $order);
    }

    /**
     * @return void
     */
    public function removeAll()
    {
        $order = $this->session->get('order', []);

        $this->session->clear($order);
    }

    /**
     * @return array
     */
    public function getFullCart(): array
    {
        $items = $this->session->get('order', []);

        $orderWithData = [];

        foreach ($items as $id => $quantity) {
            $orderWithData[] = [
                'product' => $this->productRepository->find($id),
                'quantity' => $quantity
            ];
        }

        return $orderWithData;
    }

    /**
     * @return float
     */
    public function getTotal(): float
    {
        $total = 0;

        foreach ($this->getFullCart() as $product) {
            $total += $product['product']->getPrice() * $product['quantity'];
        }

        return $total;
    }
}
