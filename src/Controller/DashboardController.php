<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Order;
use App\Entity\Product;
use App\Entity\StockItem;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class DashboardController extends AbstractController
{
    /**
     * Page d'accueil / Boutique en ligne (Accessible à TOUS, même Admin & Staff)
     */
    #[Route('/', name: 'app_home')]
    public function home(EntityManagerInterface $em): Response
    {
        $products = $em->getRepository(Product::class)->findBy(['isActive' => true]);
        $categories = $em->getRepository(Category::class)->findAll();

        return $this->render('home/index.html.twig', [
            'products' => $products,
            'categories' => $categories,
        ]);
    }

    /**
     * Dashboard réservé UNIQUEMENT à l'Admin
     */
    #[Route('/admin/dashboard', name: 'app_dashboard')]
    #[IsGranted('ROLE_ADMIN')]
    public function dashboard(EntityManagerInterface $em): Response
    {
        $totalProducts = $em->getRepository(Product::class)->count(['isActive' => true]);
        $totalOrders = $em->getRepository(Order::class)->count([]);
        
        $orders = $em->getRepository(Order::class)->findBy([], ['createdAt' => 'DESC'], 10);
        $stockAlerts = $em->getRepository(StockItem::class)->findAll();

        // Calcul du CA du jour
        $today = new \DateTimeImmutable('today');
        $ordersToday = $em->getRepository(Order::class)->createQueryBuilder('o')
            ->where('o.createdAt >= :today')
            ->setParameter('today', $today)
            ->getQuery()
            ->getResult();

        $dailyRevenue = 0.0;
        foreach ($ordersToday as $order) {
            $dailyRevenue += (float)$order->getTotalTtc();
        }

        return $this->render('admin/dashboard/index.html.twig', [
            'totalProducts' => $totalProducts,
            'totalOrders' => $totalOrders,
            'dailyRevenue' => $dailyRevenue,
            'recentOrders' => $orders,
            'stockAlerts' => $stockAlerts,
        ]);
    }
}