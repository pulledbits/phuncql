<?php
declare(strict_types=1);

namespace pulledbits\phuncql;

class PhuncqlTest extends \PHPUnit\Framework\TestCase
{
    public function testSanity_When_True_Expect_True()
    {
        $this->assertTrue(true);
    }

    public function testParseQueries_When_StingPassed_Expect_NewFunctionList() {
        $queries = parseQueries('SELECT col1, col2 FROM table');
        $this->assertArrayHasKey('col1', $queries[0](new class extends \PDO {
            public function __construct()
            {}
        }));
    }

}
