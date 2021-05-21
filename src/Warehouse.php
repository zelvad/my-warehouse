<?php


namespace Zelvad\MyWarehouse;

use GuzzleHttp\Exception\GuzzleException;
use Zelvad\MyWarehouse\Http\HttpClient;
use Zelvad\MyWarehouse\Logs\Log;

class Warehouse
{
    /**
     * Токен приложения
     *
     * @var string
     */
    private string $token;

    /**
     * @var Log
     */
    private Log $logger;

    /**
     * @var HttpClient
     */
    private HttpClient $http;

    /**
     * Warehouse constructor.
     * @param string $token
     */
    public function __construct(string $token)
    {
        $this->token = $token;

        $this->http = new HttpClient($token);
        $this->logger = new Log();
    }

    /**
     * Create
     *
     * @param array $data
     * @return array|false
     */
    public function create(array $data)
    {
        /**
         * Логируем запрос
         */
        $this->logger->log($data);

        /**
         * URL созданной группы
         */
        $url = $data['events'][0]['meta']['href'];

        try {
            /**
             * Получаем данные новой группы
             */
            $newGroup = $this->http->curl($url, 'GET');

            /**
             * Записываем ответ
             */
            $this->logger->log($newGroup);
        } catch (GuzzleException $e) {
            /**
             * Записывам ошибку в лог
             */
            $this->logger->error($e);

            return false;
        }

        if (!empty($newGroup->productFolder)) {
            /**
             * Получаем uuid родительской группы
             */
            $parent = $this->parseUrl($newGroup->productFolder->meta->href);
        }

        return [
            'id' => $newGroup->id,
            'parent_id' => isset($parent) ? $parent : null,
            'category_name' => $newGroup->name
        ];
    }

    /**
     * Update
     *
     * @param array $data
     * @return array|false
     */
    public function update(array $data)
    {
        /**
         * Логируем запрос
         */
        $this->logger->log($data);

        /**
         * URL редактируемой группы
         */
        $url = $data['events'][0]['meta']['href'];

        try {
            /**
             * Получаем данные обновляемой группы
             */
            $updateGroup = $this->http->curl($url, 'GET');

            /**
             * Записываем ответ
             */
            $this->logger->log($updateGroup);
        } catch (GuzzleException $e) {
            /**
             * Записывам ошибку в лог
             */
            $this->logger->error($e);

            return false;
        }

        if (!empty($updateGroup->productFolder)) {
            /**
             * Получаем uuid родительской группы
             */
            $parent = $this->parseUrl($updateGroup->productFolder->meta->href);
        }

        return [
            'id' => $updateGroup->id,
            'parent_id' => isset($parent) ? $parent : null,
            'category_name' => $updateGroup->name,
            'archived' => $updateGroup->archived
        ];
    }

    /**
     * Delete
     *
     * @param array $data
     * @return string[]|string[][]
     */
    public function delete(array $data): array
    {
        /**
         * Логируем запрос
         */
        $this->logger->log($data);

        /**
         * Получаем uuid удаленной группы
         */
        $id = $this->parseUrl($data['events'][0]['meta']['href']);

        return [
            'id' => $id
        ];
    }

    /**
     * Парсер URL
     *
     * @param $url
     * @return string|string[]
     */
    private function parseUrl($url)
    {
        return pathinfo(
            parse_url($url)['path'], PATHINFO_FILENAME
        );
    }
}
