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
                    return createMockPDOStatementFetchAll(['col1' => 'abcd', 'col2' => 'defg']);
            }
        });
        $queries = parseQueries('SELECT col1, col2 FROM table');
        $this->assertEquals('abcd', $queries[0]($pdo)['col1']);
        $this->assertEquals('defg', $queries[0]($pdo)['col2']);
    }

    public function testParseQueries_When_StringPassed_Expect_NewFunctionListWithAFunctionPerQuery() {
        $col3Identifier = uniqid();
        $col3Value = uniqid();

        $pdo = createMockPDOCallback();
        $pdo->callback(function(string $query, array $parameters) use ($col3Identifier, $col3Value) {
            switch ($query) {
                case 'SELECT col1, col2 FROM table':
                    return createMockPDOStatementFetchAll(['col1' => 'abcd', 'col2' => 'defg']);
                case 'SELECT ' . $col3Identifier . ', col2 FROM table':
                    return createMockPDOStatementFetchAll([$col3Identifier => $col3Value, 'col2' => 'lmno']);
            }
        });
        $queries = parseQueries('SELECT col1, col2 FROM table;SELECT ' . $col3Identifier . ', col2 FROM table;');
        $this->assertEquals('abcd', $queries[0]($pdo)['col1']);
        $this->assertEquals('defg', $queries[0]($pdo)['col2']);

        $this->assertEquals($col3Value, $queries[1]($pdo)[$col3Identifier]);
        $this->assertEquals('lmno', $queries[1]($pdo)['col2']);
    }

}
