<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;

class BaseController extends \Symfony\Bundle\FrameworkBundle\Controller\AbstractController
{
    #[Route('/', name: 'app_main')]
    public function index()
    {
        return $this->json([]);
    }
}