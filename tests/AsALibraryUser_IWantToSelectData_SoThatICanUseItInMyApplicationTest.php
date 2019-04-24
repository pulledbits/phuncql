<?php

namespace pulledbits\phuncql;

use PHPUnit\Framework\TestCase;
use function pulledbits\pdomock\createMockPDOCallback;
use function pulledbits\pdomock\createMockPDOStatement;

class AsALibraryUser_IWantToSelectData_SoThatICanUseItInMyApplicationTest extends TestCase
{

    private $pdo;
    private $connection;

    protected function setUp() : void
    {
        $this->pdo = createMockPDOCallback();
        $linkIdentifier = uniqid('mysql', true);
        pdo::$links[$linkIdentifier] = $this->pdo;
        $this->connection = connect($linkIdentifier, function(\Error $error){});

    }

    protected function tearDown() : void
    {
        pdo::$links = [];
    }

    public function testConnect()
    {
        $col3Identifier = uniqid("invoke", true);
        $col3Value = uniqid("invoke", true);

        $this->pdo->callback(function(string $query, array $parameters) use ($col3Identifier, $col3Value) : \PDOStatement {
            switch ($query) {
                case 'SELECT col1, col2 FROM table':
                    return createMockPDOStatement($query, [[$col3Identifier => $col3Value, 'col2' => 'lmno']]);
            }
        });

        $statement = ($this->connection)('SELECT col1, col2 FROM table');
        $results = $statement();
        $results(function(array $result) use ($col3Identifier, $col3Value) : void {
            $this->assertEquals($col3Value, $result[$col3Identifier]);
            $this->assertEquals('lmno', $result['col2']);
        });
    }

    public function testConnect_When_NamedPlaceholdersInQuery_Expect_RequiredParameters()
    {
        $col3Identifier = uniqid("invoke", true);
        $col3Value = uniqid("invoke", true);

        $this->pdo->callback(function(string $query, array $parameters) use ($col3Identifier, $col3Value) {
            switch ($query) {
                case 'SELECT col1, col2 FROM table WHERE col1 = ' . $parameters[0]:
                    return createMockPDOStatement($query, [[$col3Identifier => $col3Value, 'col2' => 'lmno']], $parameters, ['']);
            }
        });
        $statement = ($this->connection)('SELECT col1, col2 FROM table WHERE col1 = :col1Value');

        $this->assertFalse($statement()(function(){}));
    }

    public function testConnect_When_PlaceholdersInQuery_Expect_RequiredParameters()
    {
        $col3Identifier = uniqid("invoke", true);
        $col3Value = uniqid("invoke", true);

        $this->pdo->callback(function(string $query, array $parameters) use ($col3Identifier, $col3Value) {
            switch ($query) {
                case 'SELECT col1, col2 FROM table WHERE col1 = ?':
                    return createMockPDOStatement($query, [[$col3Identifier => $col3Value, 'col2' => 'lmno']], $parameters, ['']);
            }
        });

        $statement = ($this->connection)('SELECT col1, col2 FROM table WHERE col1 = ?');
        $this->assertFalse($statement()(function(){}));
    }

    public function testConnect_When_PlaceholdersInQueryAndParametersGiven_Expect_ResultSet()
    {
        $col3Identifier = uniqid("invoke", true);
        $col3Value = uniqid("invoke", true);

        $this->pdo->callback(function(string $query, array $parameters) use ($col3Identifier, $col3Value) {
            switch ($query) {
                case 'SELECT col1, col2 FROM table WHERE col1 = ' . $parameters[0]:
                    return createMockPDOStatement($query, [[$col3Identifier => $col3Value, 'col2' => 'lmno']], $parameters, ['abcde']);
            }
        });

        $statement = ($this->connection)('SELECT col1, col2 FROM table WHERE col1 = :col1Value');

        $results = $statement([':col1Value' => 'abcde']);

        $this->assertTrue($results(function(array $result) use ($col3Identifier, $col3Value) : void {
            $this->assertEquals($col3Value, $result[$col3Identifier]);
            $this->assertEquals('lmno', $result['col2']);
        }));
    }

    public function testConnect_When_InvalidQuery_Expect_FailedExecution()
    {
        $this->pdo->callback(function(string $query, array $parameters) {
            switch ($query) {
                case 'SELECT col1, col2 FROM table':
                    return createMockPDOStatement($query, false);
            }
        });

        $statement = ($this->connection)('SELECT col1, col2 FROM table');
        $results = $statement();

        $this->assertFalse($results(function(){}));
    }
}
