<?php
/**
 * Created by PhpStorm.
 * User: angopapo
 * Date: 27/08/18
 * Time: 11:52
 */

namespace App\Controller\home;



use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HelpController extends AbstractController
{

    /**
     * @Route("/help", name="help")
     */
    public function dashboard(){

        return $this->render('footer/help.html.twig');
    }

}