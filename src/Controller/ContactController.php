<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
#use Symfony\Component\Routing\Attribute\Route;

class ContactController extends AbstractController
{
    public function contact(): Response
    {
        return  new Response('<h1>Contact</h1>');
    }
}
