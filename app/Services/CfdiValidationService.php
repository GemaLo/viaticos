<?php

namespace App\Services;

use PhpCfdi\SatEstadoCfdi\Consumer;
use PhpCfdi\SatEstadoCfdi\HttpPsr\HttpConsumerClient;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7\HttpFactory;
use Exception;
use Illuminate\Support\Facades\Log;

class CfdiValidationService
{
    protected Consumer $consumer;

    public function __construct()
    {
        $guzzleClient = new GuzzleClient();

        $httpFactory = new HttpFactory();

        $httpConsumerClient = new HttpConsumerClient(
            $httpFactory,
            $httpFactory,
            $guzzleClient
        );

        $this->consumer = new Consumer($httpConsumerClient);
    }

    public function getCfdiStatus(string $uuid, string $rfcEmisor, string $rfcReceptor, float $total): ?array
    {
        try {
            $totalFormatted = number_format($total, 2, '.', '');

            $expression = sprintf(
                '?id=%s&re=%s&rr=%s&tt=%s',
                $uuid,
                $rfcEmisor,
                $rfcReceptor,
                $totalFormatted
            );

            $response = $this->consumer->execute($expression);

            $documentStatus = $response->getDocumentStatus();
            $cancellableStatus = $response->getCancellableStatus();
            $cancellationStatus = $response->getCancellationStatus();
            $queryStatus = $response->getQueryStatus();

            return [
                'query_status' => $queryStatus->value(),
                'document_status' => $documentStatus->value(),
                'es_vigente' => $documentStatus->isActive(),
                'es_cancelado' => $documentStatus->isCancelled(),
                'cancelable_status' => $cancellableStatus->value(),
                'cancellation_status' => $cancellationStatus->value(),
                'is_cancellable_by_direct_call' => $cancellableStatus->isCancellableByDirectCall(),
                'is_cancellable_by_approval' => $cancellableStatus->isCancellableByApproval(),
            ];

        } catch (Exception $e) {
            Log::error('Error al consultar estado de CFDI en el SAT: ' . $e->getMessage(), [
                'uuid' => $uuid,
                'rfc_emisor' => $rfcEmisor,
                'rfc_receptor' => $rfcReceptor,
                'total' => $total,
                'exception' => $e->getTraceAsString(),
            ]);
            return null;
        }
    }
}
