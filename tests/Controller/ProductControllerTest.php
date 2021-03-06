<?php

use App\Controller\ProductController;
use App\DataFixtures\ProductFixtures;
use App\DataFixtures\UserFixtures;
use App\Entity\User;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Client;

/**
 * Created by PhpStorm.
 * User: Jose
 * Date: 26/11/2018
 * Time: 20:07
 */

class ProductControllerTest extends WebTestCase
{
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
        $fixture->load($entityManager);

    }

    /**
     */
    public function testViewProduct()
    {
        //Primero pruebo que la vista de producto devuelva un 200 sin estar logueado (OK)
        $this->client = static::createClient();

        $crawler = $this->client->request('GET', '/product/1');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        //comprobamos que puede ver los comentarios y que ademas NO puede comentar
        $this->assertGreaterThan(
            0,
            $crawler->filter('#comments')->count()
        );
        $this->assertEquals(
            0,
            $crawler->filter('#newComment')->count()
        );

        //Logueamos
        $this->logIn();

        //volvemos a ver un producto y comprobamos que la respuesta sea 200(OK) y que ademas el usuario este logeado
        $crawler = $this->client->request('GET', '/product/1');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertTrue($this->client->getContainer()->get('security.token_storage')->getToken()->isAuthenticated());
        //comprobamos que puede ver los comentarios y que ademas puede comentar
        $this->assertGreaterThan(
            0,
            $crawler->filter('#comments')->count()
        );
        $this->assertGreaterThan(
            0,
            $crawler->filter('#newComment')->count()
        );


    }

    /**
     *
     * @coversNothing
     *
     * Log in de un usuario cualquiera cargado de las Fixtures
     */
    protected function logIn()
    {
        $crawler = $this->client->request('GET', '/login');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $form = $crawler->selectButton('login')->form();
        $form['email'] = $this->user->getEmail();
        $form['password'] = 'randomPassword';
        // submit the form
        $crawler = $this->client->submit($form);
        //miramos si la redireccion es correcta
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
    }


    /**
     */
    public function testNewProduct()
    {
        //Primero pruebo que la vista de crear producto devuelva un 302 si no se esta logueado (OK)
        $this->client = static::createClient();

        $crawler = $this->client->request('GET', '/product/new');

        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

        //Logueamos
        $this->logIn();

        //volvemos a intentar crear un producto y comprobamos que la respuesta sea 200(OK) y que se muestren los campos esperados
        $crawler = $this->client->request('GET', '/product/new');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());


        //comprobamos que puede introducir datos en el formulario de nuevo producto
        $this->assertGreaterThan(
            0,
            $crawler->filter('#product_name')->count()
        );

        //Seleccionamos el formulario mediante un selector por id de un input del formulario
        $form = $crawler->filter('#submit')->form();
        $form['product[name]'] = "Mesa Redonda";
        $form['product[price]'] = '5';
        $form['product[description]'] = 'Mesa redonda de madera blanca. Está prácticamente nueva, la compré por 20€.';
        $crawler = $this->client->submit($form);

        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $this->assertEquals("POST", $this->client->getRequest()->getMethod());


    }

}