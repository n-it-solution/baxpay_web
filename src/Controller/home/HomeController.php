<?php
/**
 * Created by PhpStorm.
 * User: angopapo
 * Date: 27/08/18
 * Time: 11:52
 */

namespace App\Controller\home;



use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


class HomeController extends AbstractController
{

    /**
     * @Route("/", defaults={"_locale":"%locale%"}, name="homepage")
     * @Route("/{_locale}/",requirements={"_locale":"%app_locales%"})
     * @Method("GET")
     */

    /**
     * @Route("/", name="homepage")
     */

    public function homepage(){

        //return new Response('Bemvindo ao Baxpay');
        return $this->render('home/homepage.html.twig');
    }

    /**
     * @Route("/menu", name="menu")
     */
    public function menu(){

        //return new Response('Bemvindo ao Baxpay');
        return $this->render('partials/menu.html.twig');
    }

    /**
     * @Route("/footer", name="footer")
     */
    public function footer(){

        //return new Response('Bemvindo ao Baxpay');
        return $this->render('partials/footer.html.twig');
    }

    /**
     * @Route("/dash", name="dash")
     */
    public function dash(){

        //return new Response('Bemvindo ao Baxpay');
        return $this->render('partials/dashboard.html.twig');
    }

    /**
     * @Route("/404", name="page_not_found_404")
     */
    public function NotFound404(){

        //return new Response('Bemvindo ao Baxpay');
        return $this->render('partials/404.html.twig');
    }

    /**
     * @Route("/500", name="internal_server_error_500")
     */
    public function ServerError500(){

        //return new Response('Bemvindo ao Baxpay');
        return $this->render('partials/500.html.twig');
    }

}