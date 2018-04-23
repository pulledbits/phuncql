<?php
declare(strict_types=1);

namespace pulledbits\phuncql;

use function pulledbits\pdomock\createMockPDOCallback;
use function pulledbits\pdomock\createMockPDOStatementFetchAll;

class PhuncqlTest extends \PHPUnit\Framework\TestCase
{
    public function testSanity_When_True_Expect_True()
    {
        $this->assertTrue(true);
    }

    public function testParseQueries_When_StringPassed_Expect_NewFunctionList() {
        $pdo = createMockPDOCallback();
        $pdo->callback(function(string $query, array $parameters) {
            switch ($query) {
                case 'SELECT col1, col2 FROM table':
                    return createMockPDOStatementFetchAll(['col1' => null, 'col2' => null]);
            }
        });
        $queries = parseQueries('SELECT col1, col2 FROM table');
        $this->assertArrayHasKey('col1', $queries[0]($pdo));
    }

    public function testParseQueries_When_StringPassed_Expect_NewFunctionListWithAFunctionPerQuery() {
        $pdo = createMockPDOCallback();
        $pdo->callback(function(string $query, array $parameters) {
            switch ($query) {
                case 'SELECT col1, col2 FROM table':
                    return createMockPDOStatementFetchAll(['col1' => null, 'col2' => null]);
                case 'SELECT col3, col2 FROM table':
                    return createMockPDOStatementFetchAll(['col3' => null, 'col2' => null]);
            }
        });
        $queries = parseQueries('SELECT col1, col2 FROM table;\nSELECT col3, col2 FROM table;');
        $this->assertArrayHasKey('col1', $queries[0]($pdo));
        $this->assertArrayHasKey('col3', $queries[1]($pdo));
    }

}
