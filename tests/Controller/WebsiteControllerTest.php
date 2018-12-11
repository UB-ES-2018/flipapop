<?php
/**
 * Created by PhpStorm.
 * User: luna
 * Date: 3/12/18
 * Time: 20:05
 */

namespace App\Tests\Controller;

use App\Controller\WebsiteController;
use App\DataFixtures\ProductFixtures;
use App\DataFixtures\UserFixtures;
use App\Entity\Product;
use App\Entity\User;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Client;

class WebsiteControllerTest extends WebTestCase
{
    /**
     * @var User
     */
    protected $user;

    /**
     * @var Client
     */
    protected $client;

    public function setUp() {
    $this->client = static::createClient();
    $container = self::$container;
    $doctrine = $container->get('doctrine');
    $entityManager = $doctrine->getManager();

    $metadatas = $entityManager->getMetadataFactory()->getAllMetadata();
    $schemaTool = new SchemaTool($entityManager);
    $schemaTool->updateSchema($metadatas);

    $encoder = $container->get('security.user_password_encoder.generic');

    $fixture = new UserFixtures($encoder);
    $fixture->load($entityManager);

    $userRepository = $entityManager->getRepository(User::class);


    $this->user = $userRepository->getRandomUser();

    $fixture = new ProductFixtures($userRepository);
    $fixture->load($entityManager);

}

    /**
     */
    public function testSitemapAction()
{
    //Primero pruebo que la vista de producto devuelva un 200 sin estar logueado (OK)
    $this->client = static::createClient();

    $crawler = $this->client->request('GET',  '/sitemap/sitemap.xml');

    $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

    $container = self::$container;
    $doctrine = $container->get('doctrine');
    $entityManager = $doctrine->getManager();

    $products = $entityManager->getRepository(Product::class)->findBy(array('visibility' => Product::VISIBLE_ALL));

    $numProducts = sizeof($products)+1;
    $this->assertEquals($numProducts,$crawler->filter('default|url')->count());

}
}