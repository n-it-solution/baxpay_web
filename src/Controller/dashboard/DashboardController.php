<?php
/**
 * Created by PhpStorm.
 * User: angopapo
 * Date: 27/08/18
 * Time: 11:52
 */

namespace App\Controller\dashboard;



use App\Entity\Transaction;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{

    /**
     * @Route("/myaccount/old", name="dashboardOld")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function dashboardOld(){

        return $this->render('dashboard/dashbaord.html.twig');
    }

    /**
     * @Route("/myaccount", name="myaccount")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */

    public function myAccount(){
        return $this->render('dashboard/dashboard.html.twig');
    }


    /**
     * @Route("/balance", name="balance")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function balance(){
        $em = $this->getDoctrine()->getManager();
        $received = $em->getRepository(Transaction::class)->findByReceiver($this->getUser()->getId(),1);
        $sended = $em->getRepository(Transaction::class)->findBySender($this->getUser()->getId(),1);
        $usd = $received[0][1]-$sended[0][1];
        $received = $em->getRepository(Transaction::class)->findByReceiver($this->getUser()->getId(),2);
        $sended = $em->getRepository(Transaction::class)->findBySender($this->getUser()->getId(),2);
        $eur = $received[0][1]-$sended[0][1];
        $received = $em->getRepository(Transaction::class)->findByReceiver($this->getUser()->getId(),3);
        $sended = $em->getRepository(Transaction::class)->findBySender($this->getUser()->getId(),3);
        $gbp = $received[0][1]-$sended[0][1];
        return $this->render('dashboard/balance.html.twig', [
            'usd' => $usd,
            'eur' => $eur,
            'gbp' => $gbp
        ]);
        return $this->render('dashboard/balance.html.twig');
    }
}