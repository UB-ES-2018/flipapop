<?php

//tests/Repository/ProductRepositoryTest.php
namespace App\Tests\Repository;

use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProductRepositoryTest extends WebTestCase
{
    /**
    * @var \Doctrine\ORM\EntityManager
    */
    private $entityManager;

    /**
    * {@inheritDoc}
    */
    /**
     * @var User
     */
    protected $user;

    /**
     * @var Client
     */
    protected $client;

    public function setUp()
    {
        $this->client = static::createClient();
        $container = self::$container;
        $doctrine = $container->get('doctrine');
        $entityManager = $doctrine->getManager();

        $metadatas = $entityManager->getMetadataFactory()->getAllMetadata();
        $schemaTool = new SchemaTool($entityManager);
        $schemaTool->dropSchema($metadatas);
        $schemaTool->updateSchema($metadatas);

        $encoder = $container->get('security.user_password_encoder.generic');

        $fixture = new UserFixtures($encoder);
        $fixture->load($entityManager);

        $userRepository = $entityManager->getRepository(User::class);


        $this->user = $userRepository->getRandomUser();

        $fixture = new ProductFixtures($userRepository);
        $fixture->load2($entityManager);

    }

    public function testFindBy()
    {
        $this->client = static::createClient();
        $container = self::$container;
        $doctrine = $container->get('doctrine');
        $entityManager = $doctrine->getManager();
        $productRepository = $entityManager->getRepository(Product::class);
        //$products = $this->getDoctrine()->getManager()->getRepository('App:Product')->findBy(['sold'=>false], ['numLikes'=>'DESC']);
        //coincidencias 'mesa' = 2 productos
        //coincidencias 'blanco' = 2 productos
        //coincidencias VISIBLE_ALL = 0 productos

        $numProducts = $productRepositoy->findBy(['product'=> 'mesa']).count();
        $this->assertEquals(
            2,
            $numProducts
        );

        $numProducts = $productRepositoy->findBy(['product'=> 'blanco']).count();
        $this->assertEquals(
            1,
            $numProducts
        );

        $numProducts = $productRepositoy->findBy(['product'=> 'rojo']).count();
        $this->assertEquals(
            0,
            $numProducts
        );





    }

    public function testFindOneBy()
    {
        $this->client = static::createClient();
        $container = self::$container;
        $doctrine = $container->get('doctrine');
        $entityManager = $doctrine->getManager();
        $productRepository = $entityManager->getRepository(Product::class);
        $productRepositoy->findBy(['visibility'=> VISIBLE_ALL]);


    }
}