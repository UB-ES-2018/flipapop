<?php

use App\DataFixtures\ProductFixtures;
use App\DataFixtures\UserFixtures;
use App\Entity\User;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


/**
 * Created by PhpStorm.
 * User: Jose
 * Date: 05/12/2018
 * Time: 15:31
 */

class UserControllerTest extends WebTestCase
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


    public function testViewUser()
    {
        //Primero pruebo que la vista de producto devuelva un 200 sin estar logueado (OK)
        $this->client = static::createClient();
    $crawler = $this->client->request('GET', '/view/user/1');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals("GET", $this->client->getRequest()->getMethod());

    }
  
    /**
     */
    public function testRegister(){

        $crawler = $this->client->request('GET', '/register');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        //comprobamos que puede introducir datos en el formulario de registro
        $this->assertGreaterThan(
            0,
            $crawler->filter('#user_name')->count()
        );

        //Seleccionamos el formulario mediante un selector por id de un imput del formulario
        $form = $crawler->filter('#register')->form();
        $form['user[name]'] = "Pepito";
        $form['user[surname]'] = 'Palotes';
        $form['user[email]'] = 'pepitopalotes@ub.com';
        $form['user[plainPassword][first]'] = '1234pepito';
        $form['user[plainPassword][second]'] = '1234pepito';
        $crawler = $this->client->submit($form);

        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $this->assertEquals("POST", $this->client->getRequest()->getMethod());

    }

    /**
     */
    public function testProfile(){

        $this->client = static::createClient();

        //Si no estamos logueados la pagina de perfil nos redirige a login
        $crawler = $this->client->request('GET', '/profile');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

        $container = self::$container;
        $doctrine = $container->get('doctrine');
        $entityManager = $doctrine->getManager();
        $userRepository = $entityManager->getRepository(User::class);
        $this->user = $userRepository->getRandomUser();
        #dd($this->user);
        $this->logIn();
        $crawler = $this->client->request('GET', '/profile');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        /*$text = $crawler->text();
        dd($crawler->filter('div:contains"@"'));
        $this->assertEquals(
            2,
            $crawler->filter($this->user->getName())->count()
        );*/

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