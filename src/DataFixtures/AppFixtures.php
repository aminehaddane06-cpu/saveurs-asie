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
        // 1. UTILISATEURS
        $admin = new User();
        $admin->setEmail('admin@saveursdasie.fr');
        $admin->setFirstName('Amine');
        $admin->setLastName('Haddane');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($this->hasher->hashPassword($admin, 'admin123'));
        $manager->persist($admin);

        $staff = new User();
        $staff->setEmail('staff@saveursdasie.fr');
        $staff->setFirstName('Serveur');
        $staff->setLastName('Caisse');
        $staff->setRoles(['ROLE_STAFF']);
        $staff->setPassword($this->hasher->hashPassword($staff, 'staff123'));
        $manager->persist($staff);

        // 2. CATÉGORIES
        $categoriesData = [
            ['name' => 'Entrées Chinoises', 'slug' => 'entrees-chinoises'],
            ['name' => 'Entrées Japonaises', 'slug' => 'entrees-japonaises'],
            ['name' => 'Plats Chauds', 'slug' => 'plats-chauds'],
            ['name' => 'Sushi', 'slug' => 'sushi'],
            ['name' => 'Maki & California', 'slug' => 'maki'],
            ['name' => 'Desserts', 'slug' => 'desserts'],
            ['name' => 'Boissons', 'slug' => 'boissons'],
        ];

        $categories = [];
        foreach ($categoriesData as $c) {
            $cat = new Category();
            $cat->setName($c['name']);
            if (method_exists($cat, 'setSlug')) {
                $cat->setSlug($c['slug']);
            }
            $manager->persist($cat);
            $categories[$c['slug']] = $cat;
        }

        // 3. CARTE DES PRODUITS CONSERVÉS
        $productsData = [
            // ================= BOISSONS =================
            [
                'name' => 'Coca Cola (33cl)',
                'cat' => 'boissons',
                'ttc' => 2.00,
                'vat' => '20.00',
                'desc' => 'Canette fraîche 33cl originale',
                'image' => 'https://images.unsplash.com/photo-1622483767028-3f66f32aef97?w=500&auto=format&fit=crop&q=80',
            ],
            [
                'name' => 'Fanta Orange (33cl)',
                'cat' => 'boissons',
                'ttc' => 2.00,
                'vat' => '20.00',
                'desc' => 'Boisson rafraîchissante au jus d\'orange 33cl',
                'image' => 'https://images.unsplash.com/photo-1624517452488-04869289c4ca?w=500&auto=format&fit=crop&q=80',
            ],
            [
                'name' => 'Sprite (33cl)',
                'cat' => 'boissons',
                'ttc' => 2.00,
                'vat' => '20.00',
                'desc' => 'Goût citron-citron vert intense 33cl',
                'image' => 'https://images.unsplash.com/photo-1625772299848-391b6a87d7b3?w=500&auto=format&fit=crop&q=80',
            ],
            [
                'name' => 'Ice Tea Pêche (33cl)',
                'cat' => 'boissons',
                'ttc' => 2.00,
                'vat' => '20.00',
                'desc' => 'Thé glacé saveur pêche fraîche 33cl',
                'image' => 'https://images.unsplash.com/photo-1556679343-c7306c1976bc?w=500&auto=format&fit=crop&q=80',
            ],
            [
                'name' => 'Jus de Fruits Granini (25cl)',
                'cat' => 'boissons',
                'ttc' => 3.00,
                'vat' => '20.00',
                'desc' => 'Bouteille en verre 25cl aux fruits sélectionnés',
                'image' => 'https://images.unsplash.com/photo-1600271886742-f049cd451bba?w=500&auto=format&fit=crop&q=80',
            ],
            [
                'name' => 'Jus de Litchi Asiatique',
                'cat' => 'boissons',
                'ttc' => 3.00,
                'vat' => '20.00',
                'desc' => 'Jus doux et parfumé au litchi naturel',
                'image' => 'https://images.unsplash.com/photo-1546173159-315724a31696?w=500&auto=format&fit=crop&q=80',
            ],

            // ================= DESSERTS =================
            [
                'name' => 'T3 Tarte au Chocolat (x4 parts)',
                'cat' => 'desserts',
                'ttc' => 4.50,
                'vat' => '10.00',
                'desc' => '4 parts de tarte fondante au cacao intense',
                'image' => 'https://images.unsplash.com/photo-1578985545062-69928b1d9587?w=500&auto=format&fit=crop&q=80',
            ],
            [
                'name' => 'T6 Salade de Fruits Exotiques',
                'cat' => 'desserts',
                'ttc' => 5.80,
                'vat' => '10.00',
                'desc' => 'Mélange frais de mangue, ananas, litchi et fruits du dragon',
                'image' => 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=500&auto=format&fit=crop&q=80',
            ],
            [
                'name' => 'Nougat Chinois aux Sésames',
                'cat' => 'desserts',
                'ttc' => 4.20,
                'vat' => '10.00',
                'desc' => 'Nougat tendre caramélisé enrobé de graines de sésame grillées',
                'image' => 'https://images.unsplash.com/photo-1582293041079-7814c2f12063?w=500&auto=format&fit=crop&q=80',
            ],

            // ================= ENTRÉES CHINOISES =================
            [
                'name' => 'Z1 Nems au Poulet (x5)',
                'cat' => 'entrees-chinoises',
                'ttc' => 5.80,
                'vat' => '10.00',
                'desc' => '5 nems croustillants dorés servis avec sauce nuoc-mâm et menthe',
                'image' => 'https://images.unsplash.com/photo-1544025162-d76694265947?w=500&auto=format&fit=crop&q=80',
            ],
            [
                'name' => 'Z2 Samoussa au Bœuf (x5)',
                'cat' => 'entrees-chinoises',
                'ttc' => 6.50,
                'vat' => '10.00',
                'desc' => '5 triangles croustillants farcis au bœuf haché épicé',
                'image' => 'https://images.unsplash.com/photo-1601050690597-df0568f70950?w=500&auto=format&fit=crop&q=80',
            ],
            [
                'name' => 'Z3 Nems Végétariens (x6)',
                'cat' => 'entrees-chinoises',
                'ttc' => 6.00,
                'vat' => '10.00',
                'desc' => '6 nems aux petits légumes croquants et champignons noirs',
                'image' => 'https://images.unsplash.com/photo-1541544741938-0af808871cc0?w=500&auto=format&fit=crop&q=80',
            ],
            [
                'name' => 'Z4 Samoussa aux Légumes (x8)',
                'cat' => 'entrees-chinoises',
                'ttc' => 6.00,
                'vat' => '10.00',
                'desc' => '8 samoussas dorés farcis aux légumes et curry doux',
                'image' => 'https://images.unsplash.com/photo-1589301760014-d929f3979dbc?w=500&auto=format&fit=crop&q=80',
            ],
            [
                'name' => 'Z5 Tempura de Crevettes (x7)',
                'cat' => 'entrees-chinoises',
                'ttc' => 6.30,
                'vat' => '10.00',
                'desc' => '7 beignets japonais légers et croustillants de crevettes',
                'image' => 'https://images.unsplash.com/photo-1569718212165-3a8278d5f624?w=500&auto=format&fit=crop&q=80',
            ],
            [
                'name' => 'Z6 Beignets de Crabe (x5)',
                'cat' => 'entrees-chinoises',
                'ttc' => 5.80,
                'vat' => '10.00',
                'desc' => '5 beignets moelleux à la chair de crabe assaisonnée',
                'image' => 'https://images.unsplash.com/photo-1534422298391-e4f8c172dddb?w=500&auto=format&fit=crop&q=80',
            ],
            [
                'name' => 'Z7 Beignets de Calamar (x8)',
                'cat' => 'entrees-chinoises',
                'ttc' => 6.20,
                'vat' => '10.00',
                'desc' => '8 anneaux de calamar dorés et croustillants',
                'image' => 'https://images.unsplash.com/photo-1604909052743-94e838986d24?w=500&auto=format&fit=crop&q=80',
            ],
            [
                'name' => 'Z8 Beignets de Crevettes (x7)',
                'cat' => 'entrees-chinoises',
                'ttc' => 6.80,
                'vat' => '10.00',
                'desc' => '7 crevettes royales enrobées de pâte à beignet soufflée',
                'image' => 'https://images.unsplash.com/photo-1559847844-5315695dadae?w=500&auto=format&fit=crop&q=80',
            ],
            [
                'name' => 'Z9 Assortiment de Fritures',
                'cat' => 'entrees-chinoises',
                'ttc' => 6.50,
                'vat' => '10.00',
                'desc' => 'Mélange de nems, samoussas, beignets et pinces de crabe',
                'image' => 'https://images.unsplash.com/photo-1544025162-d76694265947?w=500&auto=format&fit=crop&q=80',
            ],
            [
                'name' => 'Z10 Raviolis Crevettes Vapeur (x5)',
                'cat' => 'entrees-chinoises',
                'ttc' => 5.80,
                'vat' => '10.00',
                'desc' => '5 Ha Kao translucides à la vapeur farcis aux crevettes',
                'image' => 'https://images.unsplash.com/photo-1496116218417-1a781b1c416c?w=500&auto=format&fit=crop&q=80',
            ],
            [
                'name' => 'Z11 Bouchées de Crevettes Siu Mai (x5)',
                'cat' => 'entrees-chinoises',
                'ttc' => 5.80,
                'vat' => '10.00',
                'desc' => '5 bouchées vapeur traditionnelles cantonnaises aux crevettes',
                'image' => 'https://images.unsplash.com/photo-1563245372-f21724e3856d?w=500&auto=format&fit=crop&q=80',
            ],
            [
                'name' => 'Z12 Salade de Crevettes Fraîches',
                'cat' => 'entrees-chinoises',
                'ttc' => 7.20,
                'vat' => '10.00',
                'desc' => 'Crevettes marinées, pousses de soja, carottes et sauce sésame',
                'image' => 'https://images.unsplash.com/photo-1540420773420-3366772f4999?w=500&auto=format&fit=crop&q=80',
            ],
            [
                'name' => 'Z13 Grand Bo Bun Maison',
                'cat' => 'entrees-chinoises',
                'ttc' => 9.50,
                'vat' => '10.00',
                'desc' => 'Vermicelles de riz, bœuf sauté, nems croustillants et menthe',
                'image' => 'https://images.unsplash.com/photo-1512058564366-18510be2db19?w=500&auto=format&fit=crop&q=80',
            ],
            [
                'name' => 'Z14 Potage Pékinois Traditionnel',
                'cat' => 'entrees-chinoises',
                'ttc' => 3.80,
                'vat' => '10.00',
                'desc' => 'Soupe pékinoise aigre et piquante au tofu et champignons',
                'image' => 'https://images.unsplash.com/photo-1547592166-23ac45744acd?w=500&auto=format&fit=crop&q=80',
            ],
            [
                'name' => 'Z15 Galettes de Pommes de Terre',
                'cat' => 'entrees-chinoises',
                'ttc' => 4.80,
                'vat' => '10.00',
                'desc' => 'Galettes dorées croustillantes et fondantes',
                'image' => 'https://images.unsplash.com/photo-1589301760014-d929f3979dbc?w=500&auto=format&fit=crop&q=80',
            ],

            // ================= ENTRÉES JAPONAISES =================
            [
                'name' => 'S1 Salade de Crudités',
                'cat' => 'entrees-japonaises',
                'ttc' => 5.30,
                'vat' => '10.00',
                'desc' => 'Chou blanc croquant émincé et vinaigrette japonaise au sésame',
                'image' => 'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?w=500&auto=format&fit=crop&q=80',
            ],
            [
                'name' => 'S2 Salade d\'Algues Wakamé',
                'cat' => 'entrees-japonaises',
                'ttc' => 5.80,
                'vat' => '10.00',
                'desc' => 'Algues wakamé marinées à l\'huile de sésame et graines toastées',
                'image' => 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=500&auto=format&fit=crop&q=80',
            ],
            [
                'name' => 'S3 Salade de Betteraves Assaisonnées',
                'cat' => 'entrees-japonaises',
                'ttc' => 5.30,
                'vat' => '10.00',
                'desc' => 'Dés de betteraves tendres marinés aux fines herbes',
                'image' => 'https://images.unsplash.com/photo-1550304943-4f24f54ddde9?w=500&auto=format&fit=crop&q=80',
            ],
            [
                'name' => 'S4 Rouleaux de Printemps Frais',
                'cat' => 'entrees-japonaises',
                'ttc' => 5.20,
                'vat' => '10.00',
                'desc' => 'Galette de riz fraîche, crevettes, vermicelles et menthe',
                'image' => 'https://images.unsplash.com/photo-1534422298391-e4f8c172dddb?w=500&auto=format&fit=crop&q=80',
            ],
            [
                'name' => 'S5 Soupe Miso Traditionnelle',
                'cat' => 'entrees-japonaises',
                'ttc' => 3.50,
                'vat' => '10.00',
                'desc' => 'Bouillon de soja japonais, tofu frais, algues et ciboulette',
                'image' => 'https://images.unsplash.com/photo-1607528971899-2e89e6c0ec69?w=500&auto=format&fit=crop&q=80',
            ],
            [
                'name' => 'S6 Œufs Mayonnaise (x6 demi-pièces)',
                'cat' => 'entrees-japonaises',
                'ttc' => 5.50,
                'vat' => '10.00',
                'desc' => '6 demi-œufs frais nappés de mayonnaise onctueuse',
                'image' => 'https://images.unsplash.com/photo-1525351484163-7529414344d8?w=500&auto=format&fit=crop&q=80',
            ],
            [
                'name' => 'S7 Salade de Saumon Frais & Avocat',
                'cat' => 'entrees-japonaises',
                'ttc' => 8.80,
                'vat' => '10.00',
                'desc' => 'Dés de saumon cru d\'Écosse, avocat fondant et sauce ponzu',
                'image' => 'https://images.unsplash.com/photo-1540420773420-3366772f4999?w=500&auto=format&fit=crop&q=80',
            ],
            [
                'name' => 'S8 Salade de Thon Cuit & Avocat',
                'cat' => 'entrees-japonaises',
                'ttc' => 8.30,
                'vat' => '10.00',
                'desc' => 'Émietté de thon assaisonné, avocat en dés et salade fraîche',
                'image' => 'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?w=500&auto=format&fit=crop&q=80',
            ],

            // ================= PLATS CHAUDS =================
            [
                'name' => 'C1 Bœuf aux Oignons Tendres',
                'cat' => 'plats-chauds',
                'ttc' => 10.50,
                'vat' => '10.00',
                'desc' => 'Émincé de bœuf mariné mijoté avec oignons doux et sauce soja brune',
                'image' => 'https://images.unsplash.com/photo-1534422298391-e4f8c172dddb?w=500&auto=format&fit=crop&q=80',
            ],
            [
                'name' => 'C2 Crevettes Sautées Sel et Poivre',
                'cat' => 'plats-chauds',
                'ttc' => 10.80,
                'vat' => '10.00',
                'desc' => 'Crevettes géantes sautées au wok au sel de Guérande et poivre noir',
                'image' => 'https://images.unsplash.com/photo-1559847844-5315695dadae?w=500&auto=format&fit=crop&q=80',
            ],
            [
                'name' => 'C3 Crevettes Sauce Piquante',
                'cat' => 'plats-chauds',
                'ttc' => 10.50,
                'vat' => '10.00',
                'desc' => 'Crevettes sautées dans une sauce piquante aux piments doux et ail',
                'image' => 'https://images.unsplash.com/photo-1565557623262-b51c2513a641?w=500&auto=format&fit=crop&q=80',
            ],
            [
                'name' => 'C4 Filet de Poisson Sel et Poivre',
                'cat' => 'plats-chauds',
                'ttc' => 9.30,
                'vat' => '10.00',
                'desc' => 'Filet de poisson blanc tendre doré au sel et poivre asiatique',
                'image' => 'https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?w=500&auto=format&fit=crop&q=80',
            ],
            [
                'name' => 'C5 Filet de Poisson Sauce Piquante',
                'cat' => 'plats-chauds',
                'ttc' => 9.30,
                'vat' => '10.00',
                'desc' => 'Poisson blanc mijoté dans un coulis pimenté et légumes croquants',
                'image' => 'https://images.unsplash.com/photo-1534422298391-e4f8c172dddb?w=500&auto=format&fit=crop&q=80',
            ],
            [
                'name' => 'C6 Poulet Croustillant du Chef',
                'cat' => 'plats-chauds',
                'ttc' => 9.00,
                'vat' => '10.00',
                'desc' => 'Morceaux de poulet panés ultra croustillants, recette secrète du chef',
                'image' => 'https://images.unsplash.com/photo-1626082927389-6cd097cdc6ec?w=500&auto=format&fit=crop&q=80',
            ],
            [
                'name' => 'C7 Poulet au Caramel Traditionnel',
                'cat' => 'plats-chauds',
                'ttc' => 9.30,
                'vat' => '10.00',
                'desc' => 'Poulet fermier enrobé d\'une sauce caramel onctueuse et sésame',
                'image' => 'https://images.unsplash.com/photo-1525755662778-989d0524087e?w=500&auto=format&fit=crop&q=80',
            ],
            [
                'name' => 'C8 Poulet Sauce Piquante',
                'cat' => 'plats-chauds',
                'ttc' => 9.30,
                'vat' => '10.00',
                'desc' => 'Émincé de poulet braisé à la sauce pimentée et poivrons',
                'image' => 'https://images.unsplash.com/photo-1567620832903-9fc6debc209f?w=500&auto=format&fit=crop&q=80',
            ],
            [
                'name' => 'C9 Ailes de Poulet Frits Dorées',
                'cat' => 'plats-chauds',
                'ttc' => 7.50,
                'vat' => '10.00',
                'desc' => 'Ailes de poulet marinées et frites à cœur',
                'image' => 'https://images.unsplash.com/photo-1562967914-608f82629710?w=500&auto=format&fit=crop&q=80',
            ],
            [
                'name' => 'C10 Champignons Noirs Sautés au Wok',
                'cat' => 'plats-chauds',
                'ttc' => 7.80,
                'vat' => '10.00',
                'desc' => 'Champignons asiatiques sautés à l\'ail et sauce d\'huître',
                'image' => 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=500&auto=format&fit=crop&q=80',
            ],
            [
                'name' => 'C11 Calamars Sautés aux Légumes',
                'cat' => 'plats-chauds',
                'ttc' => 9.50,
                'vat' => '10.00',
                'desc' => 'Lamelles de calamar tendres sautées avec légumes du marché',
                'image' => 'https://images.unsplash.com/photo-1534422298391-e4f8c172dddb?w=500&auto=format&fit=crop&q=80',
            ],
            [
                'name' => 'C12 Bœuf Sauté Sauce Saté',
                'cat' => 'plats-chauds',
                'ttc' => 10.50,
                'vat' => '10.00',
                'desc' => 'Bœuf tendre nappé d\'une sauce saté parfumée aux épices et cacahuètes',
                'image' => 'https://images.unsplash.com/photo-1512058564366-18510be2db19?w=500&auto=format&fit=crop&q=80',
            ],
            [
                'name' => 'C13 Poulet au Curry Doux',
                'cat' => 'plats-chauds',
                'ttc' => 9.30,
                'vat' => '10.00',
                'desc' => 'Poulet mijoté au lait de coco et curry jaune onctueux',
                'image' => 'https://images.unsplash.com/photo-1565557623262-b51c2513a641?w=500&auto=format&fit=crop&q=80',
            ],
            [
                'name' => 'C14 Boulettes de Poulet aux Champignons Noirs',
                'cat' => 'plats-chauds',
                'ttc' => 9.80,
                'vat' => '10.00',
                'desc' => 'Boulettes de volaille maison sautées aux champignons noirs',
                'image' => 'https://images.unsplash.com/photo-1529042410759-befb1204b468?w=500&auto=format&fit=crop&q=80',
            ],
            [
                'name' => 'C15 Poulet Thaï au Basilic Frais',
                'cat' => 'plats-chauds',
                'ttc' => 8.80,
                'vat' => '10.00',
                'desc' => 'Poulet sauté au basilic thaïlandais et piments doux',
                'image' => 'https://images.unsplash.com/photo-1559847844-5315695dadae?w=500&auto=format&fit=crop&q=80',
            ],
            [
                'name' => 'C16 Poulet Sauce Aigre-Douce',
                'cat' => 'plats-chauds',
                'ttc' => 9.30,
                'vat' => '10.00',
                'desc' => 'Poulet croustillant, ananas et poivrons en sauce aigre-douce',
                'image' => 'https://images.unsplash.com/photo-1525755662778-989d0524087e?w=500&auto=format&fit=crop&q=80',
            ],
            [
                'name' => 'C17 Calamar Sauce Piquante',
                'cat' => 'plats-chauds',
                'ttc' => 9.50,
                'vat' => '10.00',
                'desc' => 'Calamars sautés au wok relevés d\'une sauce pimentée et ail',
                'image' => 'https://images.unsplash.com/photo-1563379091339-03b21ab4a4f8?w=500&auto=format&fit=crop&q=80',
            ],
            [
                'name' => 'C18 Bœuf Sauce Piquante',
                'cat' => 'plats-chauds',
                'ttc' => 10.50,
                'vat' => '10.00',
                'desc' => 'Lamelles de bœuf sautées au piment rouge et oignons',
                'image' => 'https://images.unsplash.com/photo-1534422298391-e4f8c172dddb?w=500&auto=format&fit=crop&q=80',
            ],

            // ================= SUSHIS =================
            [
                'name' => 'SU1 Sushi Saumon (x2)',
                'cat' => 'sushi',
                'ttc' => 3.80,
                'vat' => '10.00',
                'desc' => '2 sushis au saumon frais d\'Écosse label rouge sur riz vinaigré',
                'image' => 'https://images.unsplash.com/photo-1579871494447-9811cf80d66c?w=500&auto=format&fit=crop&q=80',
            ],
            [
                'name' => 'SU2 Sushi Saumon Fromage (x2)',
                'cat' => 'sushi',
                'ttc' => 3.80,
                'vat' => '10.00',
                'desc' => '2 sushis saumon nappés de cream cheese fondant',
                'image' => 'https://images.unsplash.com/photo-1617196034796-73dfa7b1fd56?w=500&auto=format&fit=crop&q=80',
            ],
            [
                'name' => 'SU3 Sushi Saumon Avocat (x2)',
                'cat' => 'sushi',
                'ttc' => 3.80,
                'vat' => '10.00',
                'desc' => '2 sushis au saumon frais et tranche d\'avocat mûr',
                'image' => 'https://images.unsplash.com/photo-1553621042-f6e147245754?w=500&auto=format&fit=crop&q=80',
            ],
            [
                'name' => 'SU4 Sushi Crevette (x2)',
                'cat' => 'sushi',
                'ttc' => 3.80,
                'vat' => '10.00',
                'desc' => '2 sushis à la crevette royale cuite',
                'image' => 'https://images.unsplash.com/photo-1611143669185-af224c5e3252?w=500&auto=format&fit=crop&q=80',
            ],
            [
                'name' => 'SU5 Sushi Œufs de Saumon Ikura (x2)',
                'cat' => 'sushi',
                'ttc' => 4.20,
                'vat' => '10.00',
                'desc' => '2 gunkan sushis garnis de généreux œufs de saumon sauvages',
                'image' => 'https://images.unsplash.com/photo-1579871494447-9811cf80d66c?w=500&auto=format&fit=crop&q=80',
            ],
            [
                'name' => 'SU6 Sushi Thon Cuit (x2)',
                'cat' => 'sushi',
                'ttc' => 3.80,
                'vat' => '10.00',
                'desc' => '2 sushis au thon cuit mariné à la mayonnaise japonaise',
                'image' => 'https://images.unsplash.com/photo-1611143669185-af224c5e3252?w=500&auto=format&fit=crop&q=80',
            ],

            // ================= MAKIS & CALIFORNIA =================
            [
                'name' => 'M1 Maki Saumon (x6)',
                'cat' => 'maki',
                'ttc' => 4.50,
                'vat' => '10.00',
                'desc' => '6 makis traditionnels au saumon frais et feuille de nori',
                'image' => 'https://images.unsplash.com/photo-1579871494447-9811cf80d66c?w=500&auto=format&fit=crop&q=80',
            ],
            [
                'name' => 'M2 Maki Thon Épicé (x6)',
                'cat' => 'maki',
                'ttc' => 4.80,
                'vat' => '10.00',
                'desc' => '6 makis au thon mariné épicé et ciboulette',
                'image' => 'https://images.unsplash.com/photo-1611143669185-af224c5e3252?w=500&auto=format&fit=crop&q=80',
            ],
            [
                'name' => 'M3 Maki Avocat Fondant (x6)',
                'cat' => 'maki',
                'ttc' => 4.20,
                'vat' => '10.00',
                'desc' => '6 makis végétariens à l\'avocat frais',
                'image' => 'https://images.unsplash.com/photo-1553621042-f6e147245754?w=500&auto=format&fit=crop&q=80',
            ],
            [
                'name' => 'M4 California Saumon Avocat (x6)',
                'cat' => 'maki',
                'ttc' => 6.50,
                'vat' => '10.00',
                'desc' => '6 california rolls saumon, avocat et graines de sésame',
                'image' => 'https://images.unsplash.com/photo-1579871494447-9811cf80d66c?w=500&auto=format&fit=crop&q=80',
            ],
            [
                'name' => 'M5 California Thon Cuit Avocat (x6)',
                'cat' => 'maki',
                'ttc' => 6.50,
                'vat' => '10.00',
                'desc' => '6 california rolls au thon cuit mariné et avocat',
                'image' => 'https://images.unsplash.com/photo-1617196034796-73dfa7b1fd56?w=500&auto=format&fit=crop&q=80',
            ],
            [
                'name' => 'M6 California Tempura Crevette (x6)',
                'cat' => 'maki',
                'ttc' => 7.20,
                'vat' => '10.00',
                'desc' => '6 california rolls à la crevette tempura croustillante',
                'image' => 'https://images.unsplash.com/photo-1611143669185-af224c5e3252?w=500&auto=format&fit=crop&q=80',
            ],
        ];

        foreach ($productsData as $p) {
            $vatRate = (float)$p['vat'];
            $priceHt = round($p['ttc'] / (1 + $vatRate / 100), 2);

            $prod = new Product();
            $prod->setName($p['name']);
            $prod->setPriceHt((string)$priceHt);
            $prod->setDescription($p['desc']);
            $prod->setVatRate($p['vat']);
            $prod->setIsActive(true);
            $prod->setCategory($categories[$p['cat']]);
            $prod->setImageUrl($p['image']);
            $manager->persist($prod);
        }

        // 4. STOCKS OPÉRATIONNELS
        $stocksData = [
            ['name' => 'Bœuf mariné (kg)', 'qty' => '25.00', 'alert' => '8.00', 'unit' => 'kg'],
            ['name' => 'Filets de poulet fermier (kg)', 'qty' => '35.00', 'alert' => '10.00', 'unit' => 'kg'],
            ['name' => 'Saumon frais Label Rouge (kg)', 'qty' => '12.00', 'alert' => '4.00', 'unit' => 'kg'],
            ['name' => 'Crevettes royales (kg)', 'qty' => '15.00', 'alert' => '5.00', 'unit' => 'kg'],
            ['name' => 'Anneaux de Calamar (kg)', 'qty' => '10.00', 'alert' => '3.00', 'unit' => 'kg'],
            ['name' => 'Riz Jasmin & Riz Sushi (kg)', 'qty' => '60.00', 'alert' => '15.00', 'unit' => 'kg'],
            ['name' => 'Feuilles de Nori (paquets)', 'qty' => '40.00', 'alert' => '10.00', 'unit' => 'unités'],
            ['name' => 'Canettes Boissons (unités)', 'qty' => '96.00', 'alert' => '24.00', 'unit' => 'unités'],
        ];

        foreach ($stocksData as $s) {
            $stock = new StockItem();
            $stock->setName($s['name']);
            if (method_exists($stock, 'setCurrentQuantity')) {
                $stock->setCurrentQuantity($s['qty']);
            }
            if (method_exists($stock, 'setMinAlertThreshold')) {
                $stock->setMinAlertThreshold($s['alert']);
            }
            $stock->setUnit($s['unit']);
            $manager->persist($stock);
        }

        $manager->flush();
    }
}