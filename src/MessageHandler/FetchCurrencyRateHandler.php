<?php

namespace App\MessageHandler;

use App\Entity\CurrencyRate;
use App\Message\FetchCurrencyRateMessage;
use App\Repository\CurrencyRateRepository;
use App\Repository\CurrencyRepository;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class FetchCurrencyRateHandler
{
    public function __construct(
        private readonly CurrencyRepository $currencyRepository,
        private readonly CurrencyRateRepository $currencyRateRepository,
    ) { }

    public function __invoke(FetchCurrencyRateMessage $message)
    {
        $client = new Client();
        $date = $message->getDate();

        $formattedDate = Carbon::createFromFormat('Y-m-d', $date)->format('d/m/Y');
        $response = $client->get('https://www.cbr.ru/scripts/XML_daily.asp', [
            'date_req' => $formattedDate,
        ]);
        $xml = simplexml_load_string($response->getBody()->getContents());

        foreach ($xml->Valute as $valute) {
            $id = (string) $valute['ID'];
            $code = (string) $valute->CharCode;
            $rate = (string) $valute->Value;
            $name = (string) $valute->Name;

            $this->currencyRepository->firstOrCreate($id, $code, $name);
            $exists = $this->currencyRateRepository->findByIdDateRate($id, $date, $rate);
            if (!$exists) {
                $this->currencyRateRepository->create($id, $date, $rate);
            }
        }
    }
}