<?php
declare(strict_types=1);

namespace pulledbits\phuncql;

use function iter\rewindable\filter;
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

        $this->assertCount(1, filter(function(callable $query) use ($pdo) {
            $result = $query($pdo);
            return $result['col1'] === 'abcd' && $result['col2'] === 'defg';
        }, $queries));
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

        $this->assertCount(2, filter(function(callable $query)  use ($pdo, $col3Identifier, $col3Value) {
            $result = $query($pdo);
            return
                (array_key_exists('col1', $result) && $result['col1'] === 'abcd' && $result['col2'] === 'defg') ||
                (array_key_exists($col3Identifier, $result) && $result[$col3Identifier] === $col3Value && $result['col2'] === 'lmno');
        }, $queries));
    }

}
