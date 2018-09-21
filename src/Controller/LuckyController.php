<?php
// src/Controller/LuckyController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/* El controlador siempre debe extender de AbstracController para poder utilizar el metodo render()*/
class LuckyController extends AbstractController
{
    /**
     * @return Response
     * @Route("lucky/number")
     */
    public function number()
    {
        $number = random_int(0, 100);

        /*
        Al metodo render le estamos pasando la localización de nuestro template. Estos siempre estan en la carpeta templates
        asi que no es necesario especifiarla. Como nuestro template (o vista) utilizará la variable $number , se tiene que pasar
        en el array de parametros del metodo. Especificamos siempre que nombre tendrá esa variable en el template.
        */
        return $this->render('lucky/number.html.twig', [
        'number' => $number,
    ]);
    }
}