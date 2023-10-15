<?php

namespace App\MessageHandler;

use App\Entity\Currency;
use App\Message\FetchAndStoreCurrencies;
use Doctrine\ORM\EntityManagerInterface;
use GuzzleHttp\Client;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Psr\Log\LoggerInterface;

#[AsMessageHandler]
class FetchAndStoreCurrenciesHandler
{
    private $entityManager;
    private $logger;

    public function __construct(EntityManagerInterface $entityManager, LoggerInterface $logger) {
        $this->entityManager = $entityManager;
        $this->logger = $logger;
    }

    public function __invoke(FetchAndStoreCurrencies $message): void
    {
        $this->logger->info('Fetching and storing currencies started1.');

        $currentDate = date('d/m/Y');
        $url = "https://www.cbr.ru/scripts/XML_daily.asp?date_req={$currentDate}";

        $client = new Client();
        $response = $client->get($url);
        $xml = simplexml_load_string($response->getBody()->getContents());

        $a = 1;

        foreach ($xml->Valute as $valute) {
            $id = (string)$valute['ID'];
            $code = (string)$valute->CharCode;
            $name = (string)$valute->Name;

            $currencyRepo = $this->entityManager->getRepository(Currency::class);
            $existingCurrency = $currencyRepo->findOneBy(['code' => $code]);

            if (!$existingCurrency) {
                $currency = new Currency();
                $currency->setId($id);
                $currency->setCode($code);
                $currency->setName($name);
                $this->entityManager->persist($currency);
            }
        }
        $this->entityManager->flush();

        $this->logger->info('Fetching and storing currencies finished.');
    }
}