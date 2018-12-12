<?php

use App\Controller\ProductController;
use App\DataFixtures\ProductFixtures;
use App\DataFixtures\UserFixtures;
use App\Entity\User;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Client;



class AjaxControllerTest extends WebTestCase
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


    public function testCambiarVisibilidadProducto()
    {
        $this->client = static::createClient();

        //si no es un 'xmlHttpRequest' redirecciona a landingPage
        $crawler = $this->client->request('POST', '/producto/visibilidad');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

        //id product aleatorio del 1 al 30
        //Valor de visibility = 1, VISIBLE_ALL
        $crawler = $this->client->xmlHttpRequest('POST','/producto/visibilidad', array('id' => random_int(1,30), 'visibility' => 1));
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals("POST", $this->client->getRequest()->getMethod());
        $responseData = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals(['visibility' => 1], $responseData);

        //id product aleatorio del 1 al 30
        //Valor de visibility = 2, VISIBLE_LOGGED
        $crawler = $this->client->xmlHttpRequest('POST','/producto/visibilidad', array('id' => random_int(1,30), 'visibility' => 2));
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals("POST", $this->client->getRequest()->getMethod());
        $responseData = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals(['visibility' => 2], $responseData);

        //id product aleatorio del 1 al 30
        //Valor de visibility = 3, VISIBLE_ME
        $crawler = $this->client->xmlHttpRequest('POST','/producto/visibilidad', array('id' => random_int(1,30), 'visibility' => 3));
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals("POST", $this->client->getRequest()->getMethod());
        $responseData = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals(['visibility' => 3], $responseData);

    }


    public function testLikeProduct()
    {
        $this->client = static::createClient();

        //si no es un 'xmlHttpRequest' redirecciona a landingPage
        $crawler = $this->client->request('POST', '/like/product');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

        //sin registrarse no se puede dar like
        $crawler = $this->client->xmlHttpRequest('POST','/like/product', array('id' => random_int(1,30)));
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

        //Logueamos
        $this->logIn();
        $this->assertTrue($this->client->getContainer()->get('security.token_storage')->getToken()->isAuthenticated());

        //id product aleatorio del 1 al 30
        $crawler = $this->client->xmlHttpRequest('POST','/like/product', array('id' => random_int(1,30)));
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals("POST", $this->client->getRequest()->getMethod());
    }


    public function testAddComment()
    {
        $this->client = static::createClient();

        //si no es un 'xmlHttpRequest' redirecciona a landingPage
        $crawler = $this->client->request('POST', '/add/comment');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

        //id product aleatorio del 1 al 30
        $crawler = $this->client->xmlHttpRequest('POST','/add/comment', array('product' => random_int(1,30), 'user' => $this->user->getId(), 'text' => "texto del comentario"));
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals("POST", $this->client->getRequest()->getMethod());

    }

    /**
     *
     */
    public function testAddReply()
    {
        $this->client = static::createClient();

        //si no es un 'xmlHttpRequest' redirecciona a landingPage
        $crawler = $this->client->request('POST', '/add/reply');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

        //id product aleatorio del 1 al 30
        $crawler = $this->client->xmlHttpRequest('POST','/add/reply', array('product' => random_int(1,30), 'user' => $this->user->getId(), 'text' => "texto de la respuesta", 'parent' => 1));
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals("POST", $this->client->getRequest()->getMethod());

    }

    /**
     */
    public function testSellProduct()
    {
        $this->client = static::createClient();

        //si no es un 'xmlHttpRequest' redirecciona a landingPage
        $crawler = $this->client->request('POST', '/sell/product');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

        //id product aleatorio del 1 al 30
        $crawler = $this->client->xmlHttpRequest('POST','/sell/product', array('id' => random_int(1,30)));
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals("POST", $this->client->getRequest()->getMethod());

    }

    /**
     *
     * @coversNothing
     *
     * Log in de un usuario cualquiera cargado de las Fixtures
     */
    protected function logIn(){
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

}