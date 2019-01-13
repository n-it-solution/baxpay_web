<?php
/**
 * Created by PhpStorm.
 * User: angopapo
 * Date: 27/08/18
 * Time: 11:52
 */

namespace App\Controller\dashboard;



use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{

    /**
     * @Route("/myaccount", name="dashboard")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function dashboard(){

        return $this->render('dashboard/dashbaord.html.twig');
    }

}