<?php

namespace App\Controller\Api;

use App\Service\Product\ProductService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/product')]
class ProductController extends AbstractController
{
    #[Route('/', name: 'app_product_index', methods: ['GET'])]
    public function index(ProductService $productService): JsonResponse
    {
        $products = $productService->getAllProducts();
        
        return new JsonResponse($products, Response::HTTP_OK);
    }

    #[Route('/new', name: 'app_product_new', methods: ['POST'])]
    public function new(ProductService $productService, Request $request): JsonResponse
    {
       
        $dataAsArray = json_decode(json: $request->getContent(), associative: true);
        if($dataAsArray === null){
            $dataAsArray = [];
        }

        $createdProduct = $productService->createOrUpdateProductFromApiRequest($dataAsArray);

        if(!$createdProduct['success']){
            return $this->json(
                $createdProduct,
                Response::HTTP_BAD_REQUEST,
                ['Content-Type' => 'application/json;charset=UTF-8']
            );
        }

        return $this->json(
            $createdProduct,
            Response::HTTP_CREATED,
            ['Content-Type' => 'application/json;charset=UTF-8']
        );
    }

    #[Route('/{id}', name: 'app_product_show', methods: ['GET'])]
    public function show(int $id, ProductService $productService): JsonResponse
    {
        $product = $productService->getProduct($id);

        if(!$product){
            return $this->json(
                [],
                Response::HTTP_NOT_FOUND,
                ['Content-Type' => 'application/json;charset=UTF-8']
            );
        }
        
        return $this->json(
            $product,
            Response::HTTP_OK,
            ['Content-Type' => 'application/json;charset=UTF-8']
        );
    }

    #[Route('/{id}/edit', name: 'app_product_edit', methods: ['PATCH'])]
    public function edit(Request $request, int $id, ProductService $productService): JsonResponse
    {
        $product = $productService->getProduct($id);

        if(!$product){
            return $this->json(
                [],
                Response::HTTP_NOT_FOUND,
                ['Content-Type' => 'application/json;charset=UTF-8']
            );
        }

        $dataAsArray = json_decode(json: $request->getContent(), associative: true);
        if($dataAsArray === null){
            $dataAsArray = [];
        }

        $updatedProduct = $productService->createOrUpdateProductFromApiRequest($dataAsArray, $product);

        if(!$updatedProduct['success']){
            return $this->json(
                $updatedProduct,
                Response::HTTP_BAD_REQUEST,
                ['Content-Type' => 'application/json;charset=UTF-8']
            );
        }

        return $this->json(
            $updatedProduct,
            Response::HTTP_OK,
            ['Content-Type' => 'application/json;charset=UTF-8']
        );
    }

    #[Route('/{id}', name: 'app_product_delete', methods: ['DELETE'])]
    public function delete(Request $request, int $id, ProductService $productService): JsonResponse
    {
        
        $product = $productService->getProduct($id);

        if(!$product){
            return $this->json(
                [],
                Response::HTTP_NOT_FOUND,
                ['Content-Type' => 'application/json;charset=UTF-8']
            );
        }

        $productService->deleteProduct($product);

        return $this->json(
            null,
            Response::HTTP_NO_CONTENT,
            ['Content-Type' => 'application/json;charset=UTF-8']
        );
    }
}
