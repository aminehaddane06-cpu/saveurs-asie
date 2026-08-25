<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Order;
use App\Entity\OrderItem;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/pos')]
#[IsGranted('ROLE_USER')]
class PosController extends AbstractController
{
    #[Route('', name: 'app_pos', methods: ['GET'])]
    public function index(EntityManagerInterface $em): Response
    {
        $categories = $em->getRepository(Category::class)->findAll();
        $products = $em->getRepository(Product::class)->findBy(['isActive' => true]);

        return $this->render('pos/index.html.twig', [
            'categories' => $categories,
            'products' => $products,
        ]);
    }

    #[Route('/checkout', name: 'app_pos_checkout', methods: ['POST'])]
    public function checkout(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!$data || empty($data['items'])) {
            return new JsonResponse(['success' => false, 'message' => 'Le panier est vide.'], 400);
        }

        $order = new Order();
        $order->setUser($this->getUser());
        $order->setType($data['type'] ?? 'SUR_PLACE');
        $order->setDeliveryAddress($data['address'] ?? null);
        $order->setStatus('PAYEE');

        $totalHt = 0.0;
        $totalTtc = 0.0;

        foreach ($data['items'] as $itemData) {
            $product = $em->getRepository(Product::class)->find($itemData['id']);
            if (!$product) {
                continue;
            }

            $qty = max(1, (int)$itemData['quantity']);
            $priceHt = (float)$product->getPriceHt();
            $vatRate = (float)$product->getVatRate();
            $priceTtc = $priceHt * (1 + $vatRate / 100);

            $orderItem = new OrderItem();
            $orderItem->setOrderRef($order);
            $orderItem->setProduct($product);
            $orderItem->setQuantity($qty);
            $orderItem->setUnitPriceHt((string)$priceHt);
            $orderItem->setVatRate((string)$vatRate);

            $totalHt += $priceHt * $qty;
            $totalTtc += $priceTtc * $qty;

            $em->persist($orderItem);
        }

        $order->setTotalHt((string)round($totalHt, 2));
        $order->setTotalTtc((string)round($totalTtc, 2));

        $em->persist($order);
        $em->flush();

        return new JsonResponse([
            'success' => true,
            'reference' => $order->getReference(),
            'totalTtc' => number_format($totalTtc, 2, ',', ' ') . ' €',
        ]);
    }
}