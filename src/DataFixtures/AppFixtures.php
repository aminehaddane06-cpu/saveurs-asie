<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\StockItem;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $hasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        // 1. Utilisateur Admin
        $admin = new User();
        $admin->setEmail('admin@saveursdasie.fr');
        $admin->setFirstName('Amine');
        $admin->setLastName('Haddane');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($this->hasher->hashPassword($admin, 'admin123'));
        $manager->persist($admin);

        // 2. Utilisateur Staff (Équipier)
        $staff = new User();
        $staff->setEmail('staff@saveursdasie.fr');
        $staff->setFirstName('Serveur');
        $staff->setLastName('Caisse');
        $staff->setRoles(['ROLE_STAFF']);
        $staff->setPassword($this->hasher->hashPassword($staff, 'staff123'));
        $manager->persist($staff);

        // 3. Catégories
        $categoriesData = [
            ['name' => 'Entrées', 'slug' => 'entrees'],
            ['name' => 'Plats Chauds', 'slug' => 'plats-chauds'],
            ['name' => 'Nouilles & Riz', 'slug' => 'nouilles-riz'],
            ['name' => 'Desserts', 'slug' => 'desserts'],
            ['name' => 'Boissons', 'slug' => 'boissons'],
        ];

        $categories = [];
        foreach ($categoriesData as $c) {
            $cat = new Category();
            $cat->setName($c['name']);
            $cat->setSlug($c['slug']);
            $manager->persist($cat);
            $categories[$c['slug']] = $cat;
        }

        // 4. Plats / Produits
        $productsData = [
            ['name' => 'Nems au Poulet (x4)', 'cat' => 'entrees', 'ht' => '5.00', 'desc' => 'Nems croustillants servis avec sauce nuoc-mâm et menthe.'],
            ['name' => 'Rouleaux de Printemps (x2)', 'cat' => 'entrees', 'ht' => '5.45', 'desc' => 'Crevettes, vermicelles de riz, menthe fraîche et salade.'],
            ['name' => 'Bo Bun Bœuf Lemongrass', 'cat' => 'plats-chauds', 'ht' => '11.36', 'desc' => 'Vermicelles de riz tièdes, bœuf sauté à la citronnelle, nems et crudités.'],
            ['name' => 'Poulet Caramel & Riz Jasmin', 'cat' => 'plats-chauds', 'ht' => '10.45', 'desc' => 'Émincé de poulet mijoté dans une sauce caramel asiatique.'],
            ['name' => 'Pad Thaï Crevettes', 'cat' => 'nouilles-riz', 'ht' => '11.82', 'desc' => 'Nouilles de riz sautées au tamarin, crevettes, œuf, cacahuètes et pousses de soja.'],
            ['name' => 'Riz Sauté Spécial Saveurs', 'cat' => 'nouilles-riz', 'ht' => '9.55', 'desc' => 'Riz sauté façon Yangzhou avec petits légumes et émincés de poulet.'],
            ['name' => 'Perles de Coco Vapeur (x2)', 'cat' => 'desserts', 'ht' => '4.09', 'desc' => 'Boules de riz gluant farcies à la crème de soja jaune, enrobées de noix de coco.'],
            ['name' => 'Mochi Glacé Thé Vert', 'cat' => 'desserts', 'ht' => '4.55', 'desc' => 'Pâtisserie japonaise glacée au matcha bio.'],
            ['name' => 'Bière Singha 33cl', 'cat' => 'boissons', 'ht' => '3.64', 'desc' => 'Bière blonde premium thaïlandaise fraîche.'],
            ['name' => 'Thé Glacé Maison Jasmin', 'cat' => 'boissons', 'ht' => '3.18', 'desc' => 'Infusion de thé vert au jasmin avec zeste de citron vert.'],
        ];

        foreach ($productsData as $p) {
            $prod = new Product();
            $prod->setName($p['name']);
            $prod->setPriceHt($p['ht']);
            $prod->setDescription($p['desc']);
            $prod->setVatRate('10.00');
            $prod->setIsActive(true);
            $prod->setCategory($categories[$p['cat']]);
            $manager->persist($prod);
        }

        // 5. Ingrédients / Stock
        $stocksData = [
            ['name' => 'Bœuf tranché (kg)', 'qty' => '14.50', 'alert' => '5.00', 'unit' => 'kg'],
            ['name' => 'Filets de poulet (kg)', 'qty' => '22.00', 'alert' => '8.00', 'unit' => 'kg'],
            ['name' => 'Crevettes crues 16/20 (kg)', 'qty' => '3.50', 'alert' => '5.00', 'unit' => 'kg'], // Alerte stock faible
            ['name' => 'Riz Jasmin parfumé (kg)', 'qty' => '45.00', 'alert' => '10.00', 'unit' => 'kg'],
            ['name' => 'Galettes de riz nems (unités)', 'qty' => '120.00', 'alert' => '30.00', 'unit' => 'unités'],
            ['name' => 'Bière Singha (bouteilles)', 'qty' => '36.00', 'alert' => '12.00', 'unit' => 'unités'],
        ];

        foreach ($stocksData as $s) {
            $stock = new StockItem();
            $stock->setName($s['name']);
            $stock->setCurrentQuantity($s['qty']);
            $stock->setMinAlertThreshold($s['alert']);
            $stock->setUnit($s['unit']);
            $manager->persist($stock);
        }

        $manager->flush();
    }
}