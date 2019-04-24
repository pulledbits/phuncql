<?php

namespace pulledbits\phuncql;

use PHPUnit\Framework\TestCase;

class AsALibraryUser_IWantToSelectData_SoThatICanUseItInMyApplicationTest extends TestCase
{

    private static $sqlite_file;
    private static $sqlite;
    private $connection;
    private $col3Value;

    static function setUpBeforeClass(): void
    {
        self::$sqlite_file = sys_get_temp_dir() . DIRECTORY_SEPARATOR . basename(__CLASS__) . '.sqlite';
        self::$sqlite = new \SQLite3(self::$sqlite_file);
        if (self::$sqlite->exec('CREATE TABLE IF NOT EXISTS persons (col1 TEXT PRIMARY KEY, col2 TEXT)') === false) {
            throw new \Exception('Can not create table persons: ' . self::$sqlite->lastErrorMsg());
        }
    }

    final protected function setUp(): void
    {
        $this->col3Value = uniqid("val", false);

        if (self::$sqlite->exec('INSERT INTO persons (col1, col2) VALUES ("' . $this->col3Value . '", "lmno")') === false) {
            throw new \Exception('Can not seed database: ' . self::$sqlite->lastErrorMsg());
        }
        $this->connection = call('pdo/connect', [])('sqlite:' . self::$sqlite_file, function (\Error $error) {
        });

    }

    public function testConnect()
    {
        $statement = ($this->connection)(function(string $dialect) : string { return 'SELECT col1, col2 FROM persons';});
        $results = $statement();
        $results(function (string $col1, string $col2): void {
            $this->assertEquals($this->col3Value, $col1);
            $this->assertEquals('lmno', $col2);
        });
    }


    public function testConnect_When_InvalidQuery_Expect_FailedExecution() : void
    {
        $connection = call('pdo/connect', [])('sqlite:' . self::$sqlite_file, function (\Error $error) {
            throw $error;
        });
        $this->expectExceptionMessageRegExp('/syntax error/');
        $connection(function(string $dialect) : string { return 'SELECT col1 col2 persons'; });
    }

    public function testConnect_When_NamedPlaceholdersInQuery_Expect_RequiredParameters()
    {
        $statement = ($this->connection)(function(string $dialect) : string { return 'SELECT col1, col2 FROM persons WHERE col1 = :col1Value'; });

        $this->assertFalse($statement()(function(string $col1, string $col2) { throw new \Exception('oops'); }));
    }

    public function testConnect_When_PlaceholdersInQuery_Expect_RequiredParameters()
    {
        $statement = ($this->connection)(function(string $dialect) : string { return 'SELECT col1, col2 FROM persons WHERE col1 = ?'; });
        $this->assertFalse($statement()(function(string $col1, string $col2) { throw new \Exception('oops'); }));
    }

    public function testConnect_When_PlaceholdersInQueryAndParametersGiven_Expect_ResultSet()
    {
        $statement = ($this->connection)(function(string $dialect) : string { return 'SELECT col1, col2 FROM persons WHERE col1 = :col1Value'; });

        $results = $statement([':col1Value' => 'abcde']);

        $this->assertTrue($results(function (string $col1, string $col2) : void {
            $this->assertEquals($this->col3Value, $col1);
            $this->assertEquals('lmno', $col2);
        }));
    }

    protected function tearDown(): void
    {
        self::$sqlite->query('DELETE FROM persons');
        self::$sqlite->query('VACUUM');
    }

    static function tearDownAfterClass(): void
    {
        self::$sqlite->close();
        unlink(self::$sqlite_file);
    }
}
