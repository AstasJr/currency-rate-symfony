<?php

namespace App\MessageHandler;

use App\Entity\Currency;
use App\Message\FetchCurrenciesMessage;
use App\Repository\CurrencyRepository;
use Doctrine\ORM\EntityManagerInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Psr\Log\LoggerInterface;

#[AsMessageHandler]
class FetchCurrenciesHandler
{
    private LoggerInterface $logger;

    public function __construct(
        LoggerInterface $appLogger,
        private readonly CurrencyRepository $currencyRepository,
    ) {
        $this->logger = $appLogger;
    }

    /**
     * @throws GuzzleException
     */
    public function __invoke(FetchCurrenciesMessage $message): void
    {
        $this->logger->info('Fetching and storing currencies started1.');

        $currentDate = date('d/m/Y');
        $url = "https://www.cbr.ru/scripts/XML_daily.asp?date_req={$currentDate}";

        $client = new Client();
        $response = $client->get($url);
        $xml = simplexml_load_string($response->getBody()->getContents());

        foreach ($xml->Valute as $valute) {
            $id = (string) $valute['ID'];
            $code = (string) $valute->CharCode;
            $name = (string) $valute->Name;

            $existingCurrency = $this->currencyRepository->findOneBy(['code' => $code]);

            if (!$existingCurrency) {
                $this->currencyRepository->create($id, $code, $name);
            }
        }

        $this->logger->info('Fetching and storing currencies finished.');
    }
}