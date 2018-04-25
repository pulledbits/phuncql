<?php

namespace pulledbits\phuncql\pdo;

use PHPUnit\Framework\TestCase;
use function pulledbits\pdomock\createMockPDOCallback;
use function pulledbits\pdomock\createMockPDOStatementFail;
use function pulledbits\pdomock\createMockPDOStatementFetchAll;
use pulledbits\phuncql\pdo;

class connectTest extends TestCase
{

    public function test__invoke()
    {
        $col3Identifier = uniqid();
        $col3Value = uniqid();

        $pdo = createMockPDOCallback();
        $pdo->callback(function(string $query, array $parameters) use ($col3Identifier, $col3Value) {
            switch ($query) {
                case 'SELECT col1, col2 FROM table':
                    return createMockPDOStatementFetchAll([[$col3Identifier => $col3Value, 'col2' => 'lmno']]);
            }
        });

        $connection = pdo::connect($pdo);
        $statement = $connection('SELECT col1, col2 FROM table');
        $results = $statement();

        $this->assertEquals($col3Value, $results[0][$col3Identifier]);
        $this->assertEquals('lmno', $results[0]['col2']);
    }


    public function test__invoke_When_InvalidQuery_Expect_FailedExecution()
    {
        $col3Identifier = uniqid();
        $col3Value = uniqid();

        $pdo = createMockPDOCallback();
        $pdo->callback(function(string $query, array $parameters) use ($col3Identifier, $col3Value) {
            switch ($query) {
                case 'SELECT col1, col2 FROM table':
                    return createMockPDOStatementFail(false);
            }
        });

        $connection = pdo::connect($pdo);
        $statement = $connection('SELECT col1, col2 FROM table');
        $results = $statement();

        $this->assertEquals([], $results);
    }
}
