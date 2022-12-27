<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

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
    public function index(): Response
    {
        return $this->json([]);
    }
}