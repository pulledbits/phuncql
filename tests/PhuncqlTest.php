<?php
declare(strict_types=1);

namespace pulledbits\phuncql;

class PhuncqlTest extends \PHPUnit\Framework\TestCase
{
    public function testSanity_When_True_Expect_True()
    {
        $this->assertTrue(true);
    }

    public function testParseQueries_When_StringPassed_Expect_NewFunctionList() {
        $pdo = new class extends \PDO {
            public function __construct()
            {}
        };
        $queries = parseQueries($pdo, 'SELECT col1, col2 FROM table');
        $this->assertArrayHasKey('col1', $queries[0]());
    }

    public function testParseQueries_When_StringPassed_Expect_NewFunctionListWithAFunctionPerQuery() {
        $pdo = new class extends \PDO {
            public function __construct()
            {}
        };
        $queries = parseQueries($pdo, 'SELECT col1, col2 FROM table;\nSELECT col3, col2 FROM table;');
        $this->assertArrayHasKey('col1', $queries[0]());
        $this->assertArrayHasKey('col3', $queries[1]());
    }

}
