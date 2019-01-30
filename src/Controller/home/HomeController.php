<?php
/**
 * Created by PhpStorm.
 * User: angopapo
 * Date: 27/08/18
 * Time: 11:52
 */

namespace App\Controller\home;



use App\Entity\Currency;
use App\Entity\Direct;
use App\Entity\Payer;
use App\Entity\Transaction;
use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
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

    public function homepage(Request $request,\Swift_Mailer $mailer){
        if(!empty($this->getUser())){
            $error = '';
            $em = $this->getDoctrine()->getManager();
            if($request->getMethod() == 'POST'){
                $data = $request->request->all();
                if ($data['form'] == 1){
                    if($data['sender'] == $this->getUser()->getUserName()){
                        $error = "You can't send money to own";
                    }
                    else{
                        $sender = $em->getRepository(User::class)->findOneBy(['username' => $data['sender'] , 'enabled' => 1]);
                        if(empty($sender)){
                            $error = 'Sender not found in database';
                        }
                        else{
                            $received = $em->getRepository(Transaction::class)->findByReceiver($this->getUser()->getId(),$data['currency']);
                            $sended = $em->getRepository(Transaction::class)->findBySender($this->getUser()->getId(),$data['currency']);
                            $total = $received[0][1]-$sended[0][1];
                            if($total >= $data['amount']){
                                $transaction = New Transaction();
                                $transaction->setAmount($data['amount']);
                                $transaction->setSender($this->getUser());
                                $transaction->setDescription($data['description']);
                                $transaction->setReceiver($sender);
                                $currency = $em->getRepository(Currency::class)->find($data['currency']);
                                $transaction->setCurrency($currency);
                                $transaction->setType(1);
                                $transaction->setDate(date('Y/m/d H:i:s'));
                                $em->persist($transaction);
                                $em->flush();
                                $error = 'Transaction send to ' . $sender->getFirstName() . ' ' . $sender->getLastName() . ' Successfully';
                                $message = (new \Swift_Message('Hello Email'))
                                    ->setFrom('mirza.amanan@gmail.com')
                                    ->setTo($sender->getEmail())
                                    ->setBody(
                                        $this->renderView(
                                        // templates/emails/registration.html.twig
                                            'emails/trans.html.twig',
                                            [
                                                'name' => $this->getUser()->getFirstName().' '.$this->getUser()->getLastName(),
                                                'receiver' => $sender->getFirstName().' '.$sender->getLastName(),
                                                'amount' => $data['amount'],
                                                'currency' => $currency->getName()
                                            ]
                                        ),
                                        'text/html'
                                    );
                                $mailer->send($message);
                            }else{
                                $error = "You don't have enough balance to send";
                            }

                        }
                    }
                }
                elseif ($data['form'] == 2){
                    if ($data['mMail'] == 'no'){
                        $payerStatus = false;
                        $checkPayer = $em->getRepository(Payer::class)->findOneBy(['email' => $data['emailCompany'] , 'User' => $this->getUser()->getId()]);
                        if (empty($checkPayer)){
                            $checkPayer2 = $em->getRepository(Payer::class)->findOneBy(['email' => $data['emailIndividual'] , 'User' => $this->getUser()->getId()]);
                            if(!empty($checkPayer2)){
                                $payerStatus = true;
                                $payer = $checkPayer2;
                            }
                        }else{
                            $payerStatus = true;
                            $payer = $checkPayer;
                        }
                        if($payerStatus != true){
                            $payer = New Payer();
                            if($request->get('emailCompany') != ''){
                                $payer->setType('c');
                                $payer->setWebsite($data['websiteURL']);
                                $payer->setFirstName($data['contactFirstNameCompany']);
                                $payer->setLastName($data['contactLastNameCompany']);
                                $payer->setEmail($data['emailCompany']);
                                $payer->setCountry($data['companyCountry']);
                            }
                            elseif ($request->get('emailIndividual') != ''){
                                $payer->setType('i');
                                $payer->setFirstName($data['contactFirstNameIndividual']);
                                $payer->setLastName($data['contactLasttNameIndividual']);
                                $payer->setEmail($data['emailIndividual']);
                                $payer->setCountry($data['indiviatualCountry']);
                            }
                            $payer->setUser($this->getUser());
                            $em->persist($payer);
                            $em->flush();
                        }
                    }else{
                        $payer = $em->getRepository(Payer::class)->findOneBy(['email' => $data['mMail']]);
                    }
                    $receiver = $em->getRepository(User::class)->findOneBy(['email' => $payer->getEmail()]);
                    $transaction = New Transaction();
                    $transaction->setAmount($data['amount']);
                    $transaction->setDescription($data['description']);
                    $transaction->setDate(date('Y/m/d H:i:s'));
                    $currency = $em->getRepository(Currency::class)->find($data['currency']);
                    $transaction->setCurrency($currency);
                    if($_FILES['file-attachment']['name'] != ""){
                        $info = pathinfo($_FILES['file-attachment']['name']);
                        $ext = $info['extension'];
                        $date = date('mdYhisms', time());
                        $newname = $date . '.' . $ext;
                        $target = 'assets/uploads/attachments/'.$newname;
                        move_uploaded_file( $_FILES['file-attachment']['tmp_name'], "./".$target);
                        $transaction->setAttachments($target);
//                            $pub->setImage($target);
                    }
                    $transaction->setDueDate($data['date']);
                    $transaction->setType(3);
                    $transaction->setReceiver($this->getUser());
                    $link = '0';
                    if(!empty($receiver)){
                        $transaction->setSender($receiver);
                        $email = $receiver->getEmail();
                        $name = $receiver->getFirstName().' '.$receiver->getLastName();
                    }else{
                        $direct = New Direct();
                        $direct->setEmail($payer->getEmail());
                        $direct->setCountry($payer->getCountry());
                        $direct->setName($payer->getFirstName().' '.$payer->getLastName());
                        $em->persist($direct);
                        $em->flush();
                        $transaction->setDirect($direct);
                        $email = $direct->getEmail();
                        $name = $direct->getName();
                    }
                    $em->persist($transaction);
                    $em->flush();
                    if (empty($receiver)){
                        $baseurl = $request->getScheme() . '://' . $request->getHttpHost() . $request->getBasePath();
                        $target_file = $this->generateUrl('single_request',['id' => $transaction->getId()]);
                        $link = $baseurl . $target_file;
                    }
                    $message = (new \Swift_Message('Transaction request received'))
                        ->setFrom('mirza.amanan@gmail.com')
                        ->setTo($email)
                        ->setBody(
                            $this->renderView(
                            // templates/emails/registration.html.twig
                                'emails/trans_req.html.twig',
                                [
                                    'name' => $this->getUser()->getFirstName().' '.$this->getUser()->getLastName(),
                                    'receiver' => $name,
                                    'amount' => $data['amount'],
                                    'currency' => $currency->getName(),
                                    'link' => $link
                                ]
                            ),
                            'text/html'
                        );
                    $mailer->send($message);
                    echo "<script>alert('Transaction request has been sent!')</script>";
                }

            }
            if ($error != '') {
                echo '<script>alert("' . $error . '")</script>';
            }
            $currency = $em->getRepository(Currency::class)->findAll();
            return $this->render('home/homepage.html.twig', [
                'currency' => $currency,
                'error' => $error,
            ]);
        }
        else{
            return $this->redirectToRoute('fos_user_security_login');
        }
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
        $currency = $this->getDoctrine()->getRepository(Currency::class)->findAll();
        $usd = $this->getDoctrine()->getRepository(Transaction::class)->findInReceiverAndSender($this->getUser()->getId(),1);
        $eur = $this->getDoctrine()->getRepository(Transaction::class)->findInReceiverAndSender($this->getUser()->getId(),2);
        $gbp = $this->getDoctrine()->getRepository(Transaction::class)->findInReceiverAndSenderWithoutCurrency($this->getUser()->getId());
        $payers = $this->getDoctrine()->getRepository(Payer::class)->findBy(['User'=>$this->getUser()->getId()],['id' => 'ASC'],4);
        $todayDate = date('Y/m/d');
//        print_r($data);
        //return new Response('Bemvindo ao Baxpay');
        return $this->render('partials/dashboard.html.twig',['usd' => $usd,'eur' => $eur, 'gbp' => $gbp,'currency' => $currency, 'payers' => $payers, 'date' => $todayDate]);
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

    /**
     * @Route("/view/profile", name="user_view_profile")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function ViewProfile(){
        return $this->render('home/profile.html.twig');
    }


}
