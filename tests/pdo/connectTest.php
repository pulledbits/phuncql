<?php

namespace pulledbits\phuncql\pdo;

use PHPUnit\Framework\TestCase;
use function pulledbits\pdomock\createMockPDOCallback;
use function pulledbits\pdomock\createMockPDOStatement;
use pulledbits\phuncql\pdo;

class connectTest extends TestCase
{

    public function test__invoke()
    {
        $col3Identifier = uniqid("invoke", true);
        $col3Value = uniqid("invoke", true);

        $pdo = createMockPDOCallback();
        $pdo->callback(function(string $query, array $parameters) use ($col3Identifier, $col3Value) {
            switch ($query) {
                case 'SELECT col1, col2 FROM table':
                    return createMockPDOStatement($query, [[$col3Identifier => $col3Value, 'col2' => 'lmno']]);
            }
        });

        $connection = pdo::connect($pdo);
        $statement = $connection('SELECT col1, col2 FROM table');
        $results = $statement();

        $this->assertEquals($col3Value, $results[0][$col3Identifier]);
        $this->assertEquals('lmno', $results[0]['col2']);
    }

    public function test__invoke_When_NamedPlaceholdersInQuery_Expect_RequiredParameters()
    {
        $col3Identifier = uniqid();
        $col3Value = uniqid();

        $pdo = createMockPDOCallback();
        $pdo->callback(function(string $query, array $parameters) use ($col3Identifier, $col3Value) {
            switch ($query) {
                case 'SELECT col1, col2 FROM table WHERE col1 = ' . $parameters[0]:
                    return createMockPDOStatement($query, [[$col3Identifier => $col3Value, 'col2' => 'lmno']], $parameters, ['']);
            }
        });

        $connection = pdo::connect($pdo);
        $statement = $connection('SELECT col1, col2 FROM table WHERE col1 = :col1Value');


        $this->expectExceptionMessageRegExp('/SQLSTATE\[HY093\]/');
        $statement();
    }

    public function test__invoke_When_PlaceholdersInQuery_Expect_RequiredParameters()
    {
        $col3Identifier = uniqid();
        $col3Value = uniqid();

        $pdo = createMockPDOCallback();
        $pdo->callback(function(string $query, array $parameters) use ($col3Identifier, $col3Value) {
            switch ($query) {
                case 'SELECT col1, col2 FROM table WHERE col1 = ?':
                    return createMockPDOStatement($query, [[$col3Identifier => $col3Value, 'col2' => 'lmno']], $parameters, ['']);
            }
        });

        $connection = pdo::connect($pdo);
        $statement = $connection('SELECT col1, col2 FROM table WHERE col1 = ?');

        $this->expectExceptionMessageRegExp('/SQLSTATE\[HY093\]/');
        $statement();
    }

    public function test__invoke_When_PlaceholdersInQueryAndParametersGiven_Expect_ResultSet()
    {
        $col3Identifier = uniqid();
        $col3Value = uniqid();

        $pdo = createMockPDOCallback();
        $pdo->callback(function(string $query, array $parameters) use ($col3Identifier, $col3Value) {
            switch ($query) {
                case 'SELECT col1, col2 FROM table WHERE col1 = ' . $parameters[0]:
                    return createMockPDOStatement($query, [[$col3Identifier => $col3Value, 'col2' => 'lmno']], $parameters, ['abcde']);
            }
        });

        $connection = pdo::connect($pdo);
        $statement = $connection('SELECT col1, col2 FROM table WHERE col1 = :col1Value');

        $results = $statement([':col1Value' => 'abcde']);

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
                    return createMockPDOStatement($query, false);
            }
        });

        $connection = pdo::connect($pdo);
        $statement = $connection('SELECT col1, col2 FROM table');
        $results = $statement();

        $this->assertEquals([], $results);
    }
}
