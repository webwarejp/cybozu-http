<?php

namespace CybozuHttp\Api\Kintone;

use GuzzleHttp\Pool;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\ResponseInterface;
use CybozuHttp\Client;
use CybozuHttp\Api\KintoneApi;

/**
 * @author ochi51 <ochiai07@gmail.com>
 */
class Records
{
    const MAX_GET_RECORDS = 500;
    const MAX_POST_RECORDS = 100;

    /**
     * @var Client
     */
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Get records
     * https://cybozudev.zendesk.com/hc/ja/articles/202331474#step2
     *
     * @param integer $appId
     * @param string $query
     * @param integer $guestSpaceId
     * @param boolean $totalCount
     * @param array|null $fields
     * @return array
     */
    public function get($appId, $query = '', $guestSpaceId = null, $totalCount = true, array $fields = null)
    {
        $options = ['json' => ['app' => $appId, 'query' => $query]];
        if ($totalCount) {
            $options['json']['totalCount'] = $totalCount;
        }
        if ($fields) {
            $options['json']['fields'] = $fields;
        }

        return $this->client
            ->get(KintoneApi::generateUrl('records.json', $guestSpaceId), $options)
            ->getBody()->jsonSerialize();
    }

    /**
     * Get all records
     *
     * @param integer $appId
     * @param string $query
     * @param integer $guestSpaceId
     * @param array|null $fields
     * @return array
     */
    public function all($appId, $query = '', $guestSpaceId = null, array $fields = null)
    {
        $result = [];
        $result[0] = $this->get($appId, $query . ' limit ' . self::MAX_GET_RECORDS, $guestSpaceId, true, $fields);
        $totalCount = $result[0]['totalCount'];
        if ($totalCount <= self::MAX_GET_RECORDS) {
            return $result[0]['records'];
        }

        $concurrency = $this->client->getConfig('concurrency');
        $requests = $this->createGetRequestsCallback($appId, $query, $guestSpaceId, $fields, $totalCount);
        $pool = new Pool($this->client, $requests(), [
            'concurrency' => $concurrency ?: 1,
            'fulfilled' => function (ResponseInterface $response, $index) use (&$result) {
                $result[$index+1] = array_merge($response->getBody()->jsonSerialize());
            }
        ]);
        $pool->promise()->wait();

        ksort($result);
        $allRecords = [];
        foreach ($result as $r) {
            /** @var array $records */
            $records = $r['records'];
            foreach ($records as $record) {
                $allRecords[] = $record;
            }
        }

        return $allRecords;
    }

    /**
     * @param integer $appId
     * @param string $query
     * @param integer $guestSpaceId
     * @param array|null $fields
     * @param integer $totalCount
     * @return \Closure
     */
    private function createGetRequestsCallback($appId, $query, $guestSpaceId, $fields, $totalCount)
    {
        $headers = $this->client->getConfig('headers');
        $headers['Content-Type'] = 'application/json';
        return function () use ($appId, $query, $guestSpaceId, $fields, $totalCount, $headers) {
            $num = ceil($totalCount / self::MAX_GET_RECORDS);
            for ($i = 1; $i < $num; $i++) {
                $body = [
                    'app' => $appId,
                    'query' => $query . ' limit ' . self::MAX_GET_RECORDS . ' offset ' . $i * self::MAX_GET_RECORDS,
                ];
                if ($fields) {
                    $body['fields'] = $fields;
                }
                yield new Request(
                    'GET',
                    KintoneApi::generateUrl('records.json', $guestSpaceId),
                    $headers,
                    \GuzzleHttp\json_encode($body)
                );
            }
        };
    }

    /**
     * Post records
     * https://cybozudev.zendesk.com/hc/ja/articles/202166160#step2
     *
     * @param integer $appId
     * @param array $records
     * @param integer $guestSpaceId
     * @return array
     */
    public function post($appId, array $records, $guestSpaceId = null)
    {
        $options = ['json' => ['app' => $appId, 'records' => $records]];

        return $this->client
            ->post(KintoneApi::generateUrl('records.json', $guestSpaceId), $options)
            ->getBody()->jsonSerialize();
    }

    /**
     * Put records
     * https://cybozudev.zendesk.com/hc/ja/articles/201941784#step2
     *
     * @param integer $appId
     * @param array $records
     * @param integer $guestSpaceId
     * @return array
     */
    public function put($appId, array $records, $guestSpaceId = null)
    {
        $options = ['json' => ['app' => $appId, 'records' => $records]];

        return $this->client
            ->put(KintoneApi::generateUrl('records.json', $guestSpaceId), $options)
            ->getBody()->jsonSerialize();
    }

    /**
     * Delete records
     * https://cybozudev.zendesk.com/hc/ja/articles/201941794
     *
     * @param integer $appId
     * @param array $ids
     * @param integer $guestSpaceId
     * @param array $revisions
     * @return array
     */
    public function delete($appId, array $ids, $guestSpaceId = null, array $revisions = [])
    {
        $options = ['json' => ['app' => $appId, 'ids' => $ids]];
        if (count($revisions) && count($ids) === count($revisions)) {
            $options['json']['revisions'] = $revisions;
        }

        return $this->client
            ->delete(KintoneApi::generateUrl('records.json', $guestSpaceId), $options)
            ->getBody()->jsonSerialize();
    }

    /**
     * Put records status
     * https://cybozudev.zendesk.com/hc/ja/articles/204791550#anchor_changeRecordStatusBulk
     *
     * @param integer $appId
     * @param array $records
     * @param integer $guestSpaceId
     * @return array
     */
    public function putStatus($appId, array $records, $guestSpaceId = null)
    {
        $options = ['json' => ['app' => $appId, 'records' => $records]];

        return $this->client
            ->put(KintoneApi::generateUrl('records/status.json', $guestSpaceId), $options)
            ->getBody()->jsonSerialize();
    }
}