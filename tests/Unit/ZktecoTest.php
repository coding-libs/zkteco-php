<?php

namespace CodingLibs\ZktecoPhp\Tests\Unit;

use CodingLibs\ZktecoPhp\Libs\ZKTeco;
use PHPUnit\Framework\TestCase;

class ZktecoTest extends TestCase
{

    public function test_zkteco()
    {
        $ZKTecoIP = getenv('APP_ZKTECO_IP');
        $ZKTecoPort = getenv('APP_ZKTECO_PORT');

        $zktecoLib = new ZKTeco($ZKTecoIP, $ZKTecoPort, false);
        $this->assertTrue($zktecoLib->connect());

        $this->assertIsString($zktecoLib->vendorName());
        $this->assertIsString($zktecoLib->deviceName());
        $this->assertIsString($zktecoLib->serialNumber());
        $this->assertIsString($zktecoLib->pinWidth());
        $this->assertIsString($zktecoLib->faceFunctionOn());
        $this->assertIsString($zktecoLib->platform());
        $this->assertIsString($zktecoLib->fmVersion());
        $this->assertIsString($zktecoLib->ssr());
        $this->assertIsString($zktecoLib->version());
        $this->assertIsString($zktecoLib->workCode());
        $this->assertIsArray($zktecoLib->getUsers());
        $this->assertIsArray($zktecoLib->getAttendances());
        $this->assertIsString($zktecoLib->getTime());
    }

}