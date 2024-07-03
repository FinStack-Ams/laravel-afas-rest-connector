<?php

namespace WeSimplyCode\LaravelAfasRestConnector;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class AfasClient
{
    /**
     * The connection to AFAS
     * @var AfasConnection
     */
    protected $connection;

    /**
     * @var AfasConnector
     */
    protected $connector;

    public function __construct(AfasConnection $connection, AfasConnector $connector)
    {
        $this->connection = $connection;
        $this->connector = $connector;
    }

    /**
     * @param string $method
     * @param array $data
     * @return Response
     * @throws \Exception
     */
    public function makeRequest(string $method, array $data = []): Response
    {
        if (!$this->connection->getToken())
        {
            throw new \Exception("AFAS token not found.");
        }

        return Http::withHeaders([
            'Authorization' => "AfasToken ".base64_encode($this->connection->getToken()),
            ...config('afas.extra_headers', [])
        ])->$method($this->connector->getUrl(), $data == [] ? null : $data);
    }
}
