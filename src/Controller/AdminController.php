<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\StockItem;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin')]
#[IsGranted('ROLE_ADMIN')]
class AdminController extends AbstractController
{
    // --- 1. GESTION DES PRODUITS / CARTE ---

    #[Route('/products', name: 'admin_products_index', methods: ['GET'])]
    public function listProducts(EntityManagerInterface $em): Response
    {
        $products = $em->getRepository(Product::class)->findAll();
        $categories = $em->getRepository(Category::class)->findAll();

        return $this->render('admin/products/index.html.twig', [
            'products' => $products,
            'categories' => $categories,
        ]);
    }

    #[Route('/products/new', name: 'admin_product_new', methods: ['POST'])]
    public function newProduct(Request $request, EntityManagerInterface $em): Response
    {
        $submittedToken = $request->request->get('_csrf_token');
        if (!$this->isCsrfTokenValid('admin_product', $submittedToken)) {
            $this->addFlash('danger', 'Jeton CSRF invalide.');
            return $this->redirectToRoute('admin_products_index');
        }

        $name = trim($request->request->get('name'));
        $categoryId = (int) $request->request->get('category_id');
        $priceHt = $request->request->get('price_ht');
        $vatRate = $request->request->get('vat_rate', '10.00');
        $description = trim($request->request->get('description'));

        $category = $em->getRepository(Category::class)->find($categoryId);

        if ($name && $category && is_numeric($priceHt)) {
            $product = new Product();
            $product->setName($name);
            $product->setCategory($category);
            $product->setPriceHt((string) $priceHt);
            $product->setVatRate((string) $vatRate);
            $product->setDescription($description);
            $product->setIsActive(true);

            $em->persist($product);
            $em->flush();

            $this->addFlash('success', 'Le plat "' . $name . '" a été ajouté au menu.');
        }

        return $this->redirectToRoute('admin_products_index');
    }

    #[Route('/products/{id}/toggle', name: 'admin_product_toggle', methods: ['POST'])]
    public function toggleProduct(Product $product, Request $request, EntityManagerInterface $em): Response
    {
        $submittedToken = $request->request->get('_csrf_token');
        if ($this->isCsrfTokenValid('toggle_prod_' . $product->getId(), $submittedToken)) {
            $product->setIsActive(!$product->isIsActive());
            $em->flush();
            $this->addFlash('info', 'Statut du plat mis à jour.');
        }

        return $this->redirectToRoute('admin_products_index');
    }

    #[Route('/products/{id}/delete', name: 'admin_product_delete', methods: ['POST'])]
    public function deleteProduct(Product $product, Request $request, EntityManagerInterface $em): Response
    {
        $submittedToken = $request->request->get('_csrf_token');
        if ($this->isCsrfTokenValid('delete_prod_' . $product->getId(), $submittedToken)) {
            $em->remove($product);
            $em->flush();
            $this->addFlash('warning', 'Plat supprimé de la carte.');
        }

        return $this->redirectToRoute('admin_products_index');
    }

    // --- 2. GESTION DES STOCKS ---

    #[Route('/stocks', name: 'admin_stocks_index', methods: ['GET'])]
    public function listStocks(EntityManagerInterface $em): Response
    {
        $stocks = $em->getRepository(StockItem::class)->findAll();

        return $this->render('admin/stocks/index.html.twig', [
            'stocks' => $stocks,
        ]);
    }

    #[Route('/stocks/{id}/update', name: 'admin_stock_update', methods: ['POST'])]
    public function updateStock(StockItem $stock, Request $request, EntityManagerInterface $em): Response
    {
        $submittedToken = $request->request->get('_csrf_token');
        if ($this->isCsrfTokenValid('update_stock_' . $stock->getId(), $submittedToken)) {
            $newQuantity = $request->request->get('quantity');
            if (is_numeric($newQuantity)) {
                $stock->setCurrentQuantity((string) $newQuantity);
                $em->flush();
                $this->addFlash('success', 'Stock de ' . $stock->getName() . ' mis à jour.');
            }
        }

        return $this->redirectToRoute('admin_stocks_index');
    }
}