<?php

namespace App\Controller;

use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;
use Symfony\Bridge\Twig\Mime\TemplatedEmail as MimeTemplatedEmail;
use Symfony\Component\Mime\Email;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mime\TemplatedEmail;

class TestForController extends AbstractController
{
    /**
     * @Route("/send_mail", name="send_email")
     */
    public function index(\Swift_Mailer $mailer, $user, $body)
    {
        // Create a message
        $message = (new \Swift_Message("Ajout de(s) nouveau(x) question dans Secu-Quizz"))
            ->setFrom("fahtialalaina2@gmail.com")
            ->setTo("alfrednicotsu@gmail.com")
            ->setBody("Salut Nico");

        $mailer->send($message);

        // return $this->render('test_for/index.html.twig', [
        //     'controller_name' => 'TestForController',
        // ]);
    }
}
