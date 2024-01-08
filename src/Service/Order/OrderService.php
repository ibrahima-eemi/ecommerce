<?php

namespace App\Service\Order;

use App\Entity\Order;
use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use App\Service\FormService;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class OrderService
{

    private ProductRepository $productRepository;
    private OrderRepository $orderRepository;
    private FormFactoryInterface $formFactory;
    
    public function __construct(ProductRepository $productRepository, OrderRepository $orderRepository, FormFactoryInterface $formFactory)
    {
        $this->productRepository = $productRepository;
        $this->orderRepository = $orderRepository;
        $this->formFactory = $formFactory;
    }

    public function getAllOrders(): array
    {
        return $this->orderRepository->findAll();
    }

    public function getOrder(int $id): ?Order
    {
        return $this->orderRepository->find($id);
    }

    public function addProductToOrder(Product $product, Order $order): Order
    {
        $order->addProduct($product);
        $this->computeTotalPrice($order);

        $this->orderRepository->save($order);

        return $order;
    }

    public function removeProductFromOrder(Product $product, Order $order): Order
    {
        $order->removeProduct($product);
        $this->computeTotalPrice($order);

        $this->orderRepository->save($order);

        return $order;
    }

    private function computeTotalPrice(Order $order): float
    {
        $totalPrice = 0;
        foreach ($order->getProducts() as $product) {
            $totalPrice += $product->getPrice();
        }

        $order->setTotalPrice($totalPrice);

        return $totalPrice;
    }

    public function deleteOrder(Order $order): void
    {
        $this->orderRepository->delete($order);
    }
}