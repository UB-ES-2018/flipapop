<?php

namespace App\Controller;

use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @Route("/product", name="product")
     */
    public function index()
    {
        /*El EntityManager es el objeto que nos permite acceder a todas las Entidades de nuestro proyecto*/
        $entityManager = $this->getDoctrine()->getManager();

        //esto es muy Java
        $product = new Product();
        $product->setName('Keyboard');
        $product->setPrice(1999);
        $product->setDescription('Ergonomic and stylish!');

        // Con la función perist() guardamos la el objeto de la entidad que acabamos de crear
        // Todavia no hemos guardado nada, simplemente hemos puesto "en cola" nuestro objeto para ser guardado
        $entityManager->persist($product);

        //Es aqui cuando se hace el "INSERT" en base de datos, asi de facil todo.
        $entityManager->flush();

        return new Response('Saved new product with id '.$product->getId());
    }

    /**
     * @Route("/product/{id}", name="product_show")
     */
    public function show($id)
    {
        /* aqui Doctrine, el ORM se encarga de ir al repositorio de Producto i buscar aquel que coincida con la id*/
        // Teneis que entender los Repositorios como los encargados de recoger de base de datos las entidades, tanto de 1 en 1 como varias a la vez

        $product = $this->getDoctrine()
            ->getRepository(Product::class)
            ->find($id);

        //podemos buscar también por nombre por ejemplo lo que nos devuelve un array de Productos con ese nombre

        //la funcion dump() nos muestra por pantalla lo que le pasemos
        // descomentar este codigo para probarlo
        /*
        $products = $this->getDoctrine()->getRepository(Product::class)->findBy(['name' => "Keyboard"]);
        dump($products);
        */


        if (!$product) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }

        return new Response('Check out this great product: '.$product->getName());

        // or render a template
        // in the template, print things with {{ product.name }}
        // return $this->render('product/show.html.twig', ['product' => $product]);
    }

    /**
     * @Route("/product/show/{id}", name="product_show_rapido")
     */
    public function show2(Product $product)
    {
        // use the Product!
        // ...

        if (!$product) {
            throw $this->createNotFoundException(
                'No product found'
            );
        }

        return new Response('Check out this great product: '.$product->getName());
    }
}
