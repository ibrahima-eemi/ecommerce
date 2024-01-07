<?php

namespace App\Service\Product;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use App\Service\FormService;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class ProductService
{

    private ProductRepository $productRepository;
    private FormFactoryInterface $formFactory;
    
    public function __construct(ProductRepository $productRepository, FormFactoryInterface $formFactory)
    {
        $this->productRepository = $productRepository;
        $this->formFactory = $formFactory;
    }

    public function getAllProducts(): array
    {
        return $this->productRepository->findAll();
    }

    public function createOrUpdateProductFromWebRequest(Request $request, Product $product = null): FormInterface
    {
        $form = $this->formFactory->create(ProductType::class, $product);
        $form->handleRequest($request);

        $result = $this->createOrUpdateProduct($form);

        return $result['form'];
    }

    public function createOrUpdateProductFromApiRequest(array $data, Product $product = null): array
    {
        $form = $this->formFactory->create(ProductType::class, $product);
        $form->submit($data);

        $result = $this->createOrUpdateProduct($form);

        return [
            'success' => $result['success'],
            'product' => $result['product']
        ];
    }

    private function createOrUpdateProduct(FormInterface $form) 
    { 
        if (!$form->isValid()) {
            $formService = new FormService();
            $errors = $formService->getFormErrors($form);
            return [
                'success' => false,
                'errors' => $errors,
                'form' => $form
            ];
        }

        $product = $form->getData();

        $this->productRepository->save($product);

        return [
            'success' => true,
            'product' => $product,
            'form' => $form
        ];
    }

    public function getProduct(int $id): ?Product
    {
        return $this->productRepository->find($id);
    }

    public function deleteProduct(Product $product): void
    {
        $this->productRepository->delete($product);
    }
}