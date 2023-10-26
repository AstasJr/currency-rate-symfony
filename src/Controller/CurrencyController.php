<?php

namespace App\Controller;

use App\Repository\CurrencyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CurrencyController extends AbstractController
{
    #[Route('/currency', name: 'app_currency')]
    public function index(CurrencyRepository $currencyRepository): JsonResponse
    {
        $currencies = $currencyRepository->findAll();

        if (!$currencies) {
            return $this->json(
                ['message' => 'No currencies found'],
                Response::HTTP_NOT_FOUND,
            );
        }

        $currenciesArray = array_map(function ($currency) {
            return [
                'id' => $currency->getId(),
                'code' => $currency->getCode(),
                'name' => $currency->getName(),
            ];
        }, $currencies);

        return $this->json([
            'currencies' => $currenciesArray,
        ]);
    }
}
