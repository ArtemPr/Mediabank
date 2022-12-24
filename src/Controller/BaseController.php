<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class BaseController extends AbstractController
{
    #[Route(
        [
            '/',
            '/api'
        ],
        name: 'api_lock',
        methods: [
            'GET',
            'POST'
        ]
    )]
    public function index()
    {
        return $this->json([]);
    }
}