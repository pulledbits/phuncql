<?php
declare(strict_types=1);

namespace pulledbits\phuncql;

class PhuncqlTest extends \PHPUnit\Framework\TestCase
{
    public function testSanity_When_True_Expect_True()
    {
        $this->assertTrue(true);
    }

    public function testParseQueries_When_StreamPassed_Expect_NewFunctionList() {
        $stream = fopen("php://memory", 'rw+');
        fwrite($stream, 'SELECT * FROM table');
        fseek($stream, 0);
        $queries = parseQueries($stream);
        $this->assertInstanceOf('Closure', $queries[0]);
    }
}
