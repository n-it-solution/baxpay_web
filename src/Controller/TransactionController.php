<?php

namespace App\Controller;

use App\Entity\Currency;
use App\Entity\Direct;
use App\Entity\Transaction;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TransactionController extends AbstractController
{
    public function partial(){
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
        return $this->render('transaction/partial.html.twig', [
            'usd' => $usd,
            'eur' => $eur,
            'gbp' => $gbp
        ]);
    }
    /**
     * @Route("/transaction", name="transaction")
     */
    public function index(Request $request,\Swift_Mailer $mailer)
    {
        $error = '';
        $em = $this->getDoctrine()->getManager();
        $data = $em->getRepository(Transaction::class)->findByReceiver($this->getUser()->getId(),1);
//        print_r($data);
        $data1 = $em->getRepository(Transaction::class)->findBySender($this->getUser()->getId(),1);
////        print_r($data1);
//        if(empty($data)){
//            echo 'hello';
//        }
//        else{
//            print_r($data[0][1]-$data1[0][1]);
////            print_r($data1[0][1]);
//        }
        if($request->getMethod() == 'POST'){
            $data = $request->request->all();
            if($data['sender'] == $this->getUser()->getUserName()){
                $error = "You can't send money to own";
            }
            else{
                $sender = $em->getRepository(User::class)->findOneBy(['username' => $data['sender'] , 'enabled' => 1]);
                if(empty($sender)){
                    $error = 'Send username not found in database';
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
        $currency = $em->getRepository(Currency::class)->findAll();
        $transactions = $this->getDoctrine()->getRepository(Transaction::class)->findInReceiverAndSenderWithoutCurrency($this->getUser()->getId());
        return $this->render('transaction/transaction2.html.twig', [
            'currency' => $currency,
            'error' => $error,
            'transactions' => $transactions
        ]);
    }
    /**
     * @Route("/request/send", name="request_send")
     */
    public function reqSend(Request $request,\Swift_Mailer $mailer){
        $em = $this->getDoctrine()->getManager();
        $error = '';
        if($request->getMethod() == 'POST'){
            $data = $request->request->all();
            if($data['receiver'] == $this->getUser()->getEmail()){
                $error = "You can't request money to own";
            }
            else{
                $receiver = $em->getRepository(User::class)->findOneBy(['email' => $data['receiver'] , 'enabled' => 1]);
                if(empty($receiver)){
                    $error = 'Send username not found in database';
                }
                else{
                    $transaction = New Transaction();
                    $transaction->setAmount($data['amount']);
                    $transaction->setSender($receiver);
                    $transaction->setDescription($data['description']);
                    $transaction->setReceiver($this->getUser());
                    $currency = $em->getRepository(Currency::class)->find($data['currency']);
                    $transaction->setCurrency($currency);
                    $transaction->setType(3);
                    $transaction->setDate(date('Y/m/d H:i:s'));
                    $em->persist($transaction);
                    $em->flush();
                    $error = 'Transaction send to ' . $receiver->getFirstName() . ' ' . $receiver->getLastName() . ' Successfully';
                    $message = (new \Swift_Message('Hello Email'))
                        ->setFrom('mirza.amanan@gmail.com')
                        ->setTo($receiver->getEmail())
                        ->setBody(
                            $this->renderView(
                            // templates/emails/registration.html.twig
                                'emails/trans_req.html.twig',
                                [
                                    'name' => $this->getUser()->getFirstName().' '.$this->getUser()->getLastName(),
                                    'receiver' => $receiver->getFirstName().' '.$receiver->getLastName(),
                                    'amount' => $data['amount'],
                                    'currency' => $currency->getName()
                                ]
                            ),
                            'text/html'
                        );
                    $mailer->send($message);
                }
            }
            }
        $currency = $em->getRepository(Currency::class)->findAll();
        return $this->render('transaction/req_send.html.twig', [
            'currency' => $currency,
            'error' => $error
        ]);
    }
    /**
     * @Route("/request/received2", name="request_received2")
     */
    public function reqReceived2(){
        $em = $this->getDoctrine()->getManager();
        $req = $em->getRepository(Transaction::class)->findBy(['Sender' => $this->getUser()->getId(), 'Type' => 3]);
        return $this->render('transaction/request_reseived.html.twig',[
            'req' => $req,
        ]);
    }

    /**
     * @Route("/request/received", name="request_received")
     */
    public function reqReceived(Request $request,\Swift_Mailer $mailer)
    {
        $em = $this->getDoctrine()->getManager();
        $error = '';
        $req = $em->getRepository(Transaction::class)->findBy(['Sender' => $this->getUser()->getId(), 'Type' => 3]);
        return $this->render('transaction/req_received.html.twig', [
            'req' => $req,
            'error' => $error
        ]);
    }
    /**
     * @Route("/request/action", name="request_action")
     */

    public function reqAction(Request $request,\Swift_Mailer $mailer)
    {
        $em = $this->getDoctrine()->getManager();
        $msg = '';
        $action = $request->get('action');
        $trans = $em->getRepository(Transaction::class)->find($request->get('id'));
        $trans->getId();
        if (!empty($trans)) {
            if ($trans->getType() == 3) {
            if ($trans->getSender()->getId() == $this->getUser()->getId()) {
                $received = $em->getRepository(Transaction::class)->findByReceiver($this->getUser()->getId(), $trans->getCurrency()->getId());
                $sended = $em->getRepository(Transaction::class)->findBySender($this->getUser()->getId(), $trans->getCurrency()->getId());
                $total = $received[0][1] - $sended[0][1];
            if ($action == 'reject'){
                $trans->setType(4);
                $em->flush();
                $msg = 'Record updated';
            }
            elseif ($total >= $trans->getAmount()) {
                    if($action == 'accept'){
                        if(date('Y/m/d') <= $trans->getDueDate()){
                            $trans->setType(2);
                            $msg = 'Record updated';
                            $em->flush();
                        }else{
                            $msg = 'Request expired';
                        }
                    }
                } else {
                    $msg = 'You did not have enough balanced to accept this request';
                }
            } else {
                $msg = 'Something Wrong please try again!';
            }
            }else{
                $msg = 'Something wrong with trying to update';
            }
        }else{
            echo 2;
            $msg = 'Something Wrong please try again!';
        }
//        $req = $em->getRepository(Transaction::class)->findBy(['Sender' => $this->getUser()->getId(), 'Type' => 3]);
        echo "<script>alert('".$msg."')</script>";
        return $this->redirectToRoute('request_received2');
    }
    /**
     * @Route("/direct/send", name="direct_send")
     */
    public function directSend(Request $request,\Swift_Mailer $mailer){
        $em = $this->getDoctrine()->getManager();
        $error = '';
        if ($request->getMethod() == 'POST'){
            $data = $request->request->all();
            $sender = $em->getRepository(User::class)->findOneBy(['username' => $data['sender'] , 'enabled' => 1]);
            if(empty($sender)){
                $error = 'Account not available';
            }else{
                $direct = New Direct();
                $direct->setName($data['name']);
                $direct->setCountry($data['country']);
                $em->persist($direct);
                $em->flush();
                $transaction = New Transaction();
                $transaction->setAmount($data['amount']);
                $transaction->setDirect($direct);
                $transaction->setDescription($data['description']);
                $transaction->setReceiver($sender);
                $currency = $em->getRepository(Currency::class)->find($data['currency']);
                $transaction->setCurrency($currency);
                $transaction->setType(0);
                $transaction->setDate(date('Y/m/d H:i:s'));
                $em->persist($transaction);
                $em->flush();
                $error = 'Amount sent.';
                $message = (new \Swift_Message('Hello Email'))
                    ->setFrom('mirza.amanan@gmail.com')
                    ->setTo($sender->getEmail())
                    ->setBody(
                        $this->renderView(
                        // templates/emails/registration.html.twig
                            'emails/trans.html.twig',
                            [
                                'name' => $data['name'],
                                'receiver' => $sender->getFirstName().' '.$sender->getLastName(),
                                'amount' => $data['amount'],
                                'currency' => $currency->getName()
                            ]
                        ),
                        'text/html'
                    );
                $mailer->send($message);
            }
        }
        $currency = $em->getRepository(Currency::class)->findAll();
        return $this->render('transaction/direct_send.html.twig', [
            'currency' => $currency,
            'error' => $error
        ]);
    }

    /**
     * @Route("/pay/{id}", name="single_request")
     */
    public function singleReq($id){

        $data = $this->getDoctrine()->getRepository(Transaction::class)->find($id);
        return $this->render('transaction/single_req.html.twig',[
            'data' => $data
        ]);
    }
    /**
     * @Route("/pay/action/{id}/{action}", name="single_request_action")
     */
    public function singleReqAction($id,$action){
        $em = $this->getDoctrine()->getManager();
        $data = $em->getRepository(Transaction::class)->find($id);
        if($action == 'accept'){
            if(date('Y/m/d') <= $data->getDueDate()){
                $data->setType(2);
                $msg = 'Request completed';
                $em->flush();
            }else{
                $msg = 'Request expired';
            }
        }elseif ($action == 'reject'){
            $data->setType(4);
            $msg = 'Request Rejected';
            $em->flush();
        }
        echo "<script>alert('".$msg."')</script>";
        return $this->redirectToRoute('single_request',['id' => $id]);
    }

}
