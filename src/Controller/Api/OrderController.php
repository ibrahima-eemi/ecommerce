<?php

namespace App\Controller\Api;

use App\Service\Order\OrderService;
use App\Service\Product\ProductService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/order')]
class OrderController extends AbstractController
{
    #[Route('/', name: 'app_order_index', methods: ['GET'])]
    public function index(OrderService $orderService): JsonResponse
    {
        $orders = $orderService->getAllOrders();
        
        return new JsonResponse($orders, Response::HTTP_OK);
    }

    #[Route('/{id}', name: 'app_order_show', methods: ['GET'])]
    public function show(int $id, OrderService $orderService): JsonResponse
    {
        $order = $orderService->getOrder($id);

        if(!$order){
            return $this->json(
                [],
                Response::HTTP_NOT_FOUND,
                ['Content-Type' => 'application/json;charset=UTF-8']
            );
        }
        
        return $this->json(
            $order,
            Response::HTTP_OK,
            ['Content-Type' => 'application/json;charset=UTF-8']
        );
    }

    #[Route('/{id}/product/{productId}/add', name: 'app_order_add_product', methods: ['GET'])]
    public function addProductToOrder(int $id, int $productId, OrderService $orderService, ProductService $productService): JsonResponse
    {
        $order = $orderService->getOrder($id);

        if(!$order){
            return $this->json(
                [],
                Response::HTTP_NOT_FOUND,
                ['Content-Type' => 'application/json;charset=UTF-8']
            );
        }

        $product = $productService->getProduct($productId);

        if(!$product){
            return $this->json(
                [],
                Response::HTTP_NOT_FOUND,
                ['Content-Type' => 'application/json;charset=UTF-8']
            );
        }

        $order = $orderService->addProductToOrder($product, $order);
        
        return $this->json(
            $order,
            Response::HTTP_OK,
            ['Content-Type' => 'application/json;charset=UTF-8']
        );
    }

    #[Route('/{id}/product/{productId}/remove', name: 'app_order_remove_product', methods: ['GET'])]
    public function removeProductFromOrder(int $id, int $productId, OrderService $orderService, ProductService $productService): JsonResponse
    {
        $order = $orderService->getOrder($id);

        if(!$order){
            return $this->json(
                [],
                Response::HTTP_NOT_FOUND,
                ['Content-Type' => 'application/json;charset=UTF-8']
            );
        }

        $product = $productService->getProduct($productId);

        if(!$product){
            return $this->json(
                [],
                Response::HTTP_NOT_FOUND,
                ['Content-Type' => 'application/json;charset=UTF-8']
            );
        }

        $order = $orderService->removeProductFromOrder($product, $order);
        
        return $this->json(
            $order,
            Response::HTTP_OK,
            ['Content-Type' => 'application/json;charset=UTF-8']
        );
    }
}
