<?php


namespace Zelvad\MyWarehouse\Http;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\StreamInterface;

class HttpClient
{
    /**
     * Токен приложения
     *
     * @var string
     */
    private string $token;

    /**
     * @var Client
     */
    private Client $client;

    /**
     * HttpClient constructor.
     * @param string $token
     */
    public function __construct(string $token)
    {
        $this->token = config('my-warehouse.token');

        /**
         * Иницилизируем клиент
         */
        $this->client = new Client();
    }

    /**
     * CURL
     *
     * @param string $url
     * @param string $method
     * @param array $params
     * @return mixed
     * @throws GuzzleException
     */
    public function curl(string $url, string $method, array $params = [])
    {
        $result = $this->client->request(
            $method, $url, $params + [
                'headers' => [
                    'Authorization' => 'Bearer '.$this->token,
                    'Content-Type' => 'application/json'
                ]
            ]
        )
            ->getBody()
            ->getContents();

        return json_decode($result);
    }
}
