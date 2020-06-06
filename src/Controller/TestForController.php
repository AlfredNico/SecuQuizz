<?php

namespace App\Controller;

use Symfony\Bridge\Twig\Mime\TemplatedEmail as MimeTemplatedEmail;
use Symfony\Component\Mime\Email;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mime\TemplatedEmail;

class TestForController extends AbstractController
{
    /**
     * @Route("/send-mail", name="sent_email")
     */
    public function index(\Swift_Mailer $mailer)
    {
        $message = (new \Swift_Message('Hello Email'))
        ->setFrom('alfrednicotsu@gmai.com')
        ->setTo('alfrednicotsu@gmail.com')
        ->setBody(
            $this->renderView('test_for/index.html.twig', [
                'controller_name' => 'TestForController',
            ])
        )
    ;
    $mailer->send($message);
            
        return $this->render('test_for/index.html.twig', [
            'controller_name' => 'TestForController',
        ]);
    }
    
}
