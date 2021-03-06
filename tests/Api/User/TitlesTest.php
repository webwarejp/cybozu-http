<?php

namespace CybozuHttp\Tests\Api\User;

require_once __DIR__ . '/../../_support/UserTestHelper.php';
use UserTestHelper;

use EasyCSV\Reader;
use CybozuHttp\Api\UserApi;

/**
 * @author ochi51 <ochiai07@gmail.com>
 */
class TitlesTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var UserApi
     */
    private $api;

    protected function setup()
    {
        $this->api = UserTestHelper::getUserApi();
    }

    public function testCsv()
    {
        $filename = __DIR__ . '/../../_data/titles.csv';
        $id = $this->api->titles()->postByCsv($filename);
        while (1) {
            $result = $this->api->csv()->result($id);
            if (!$result['done']) {
                continue;
            }
            if ($result['success']) {
                self::assertTrue(true);
            } else {
                self::fail($result['errorCode']);
            }
            break;
        }

        $content = $this->api->titles()->getByCsv();
        $path = __DIR__ . '/../../_output/export-titles.csv';
        file_put_contents($path, $content);
        $getCsv = new Reader($path, 'r+', false);
        $flg1 = $flg2 = false;
        while ($row = $getCsv->getRow()) {
            if ("example-title1" == reset($row)) {
                $flg1 = true;
            }
            if ("example-title2" == reset($row)) {
                $flg2 = true;
            }
        }
        self::assertTrue($flg1 and $flg2);

        $filename = __DIR__ . '/../../_data/delete-titles.csv';
        $id = $this->api->titles()->postByCsv($filename);
        while (1) {
            $result = $this->api->csv()->result($id);
            if (!$result['done']) {
                continue;
            }
            if ($result['success']) {
                self::assertTrue(true);
            } else {
                self::fail($result['errorCode']);
            }
            break;
        }
    }

}
