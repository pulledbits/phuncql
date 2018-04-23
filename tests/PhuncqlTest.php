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
        fwrite($stream, 'SELECT col1, col2 FROM table');
        fseek($stream, 0);
        $queries = parseQueries($stream);
        $this->assertArrayHasKey('col1', $queries[0](new class extends \PDO {
            public function __construct()
            {}
        }));
    }
}
