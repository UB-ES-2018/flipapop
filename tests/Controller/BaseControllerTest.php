<?php

use App\Controller\ProductController;
use App\DataFixtures\ProductFixtures;
use App\DataFixtures\UserFixtures;
use App\Entity\User;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Client;
use Symfony\Component\HttpFoundation\Request;

/**
 * Created by PhpStorm.
 * User: Carles
 * Date: 27/11/2018
 * Time: 19:07
 */

class BaseControllerTest extends WebTestCase
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
    public function testViewHomePage()
    {
        //Primero pruebo que la vista de producto devuelva un 200 sin estar logueado (OK)
        $this->client = static::createClient();

        $crawler = $this->client->request('GET', '/');

        //comprobamos que puede ver los comentarios y que ademas NO puede comentar
        /*$this->assertGreaterThan(
            0,
            $crawler->filter('#product')->count()
        );*/

    }

    public function testViewHomePageWithSearchbar()
    {
        //Primero pruebo que la vista de producto devuelva un 200 sin estar logueado (OK)
        $this->client = static::createClient();
        $crawler = $this->client->request('GET', '/');

        //comprobamos que puede ver los comentarios y que ademas NO puede comentar
        /*$this->assertGreaterThan(
            0,
            $crawler->filter('#product')->count()
        );*/

    }

}