<?php
declare(strict_types=1);

namespace pulledbits\phuncql;


use function pulledbits\pdomock\createMockPDOCallback;

class AsALibaryUser_IWantToConnectToASQLDatabase_SoThatICanInteractWithItTest extends \PHPUnit\Framework\TestCase
{
    private static $sqlite_file;
    private static $sqlite;

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

    }

    public function testSanity() : void
    {
        $this->assertTrue(true);
    }

    final public function testConnect_WhenValidDSN_ExpectNoError() : void {
        $querier = connect('sqlite:' . self::$sqlite_file, function(\Error $error){
            throw $error;
        });
        $this->assertInstanceOf('Closure', $querier);
    }

    final public function testConnect_WhenInvalidDSN_ExpectError() : void {
        $this->expectExceptionMessage('Unable to connect: invalid data source name');
        connect(self::$sqlite_file, function(\Error $error) {
            throw $error;
        });
    }


    final public function test() : void {
        $querier = connect('sqlite:' . self::$sqlite_file, function(\Error $error){});
        $query = $querier('SELECT * FROM persons');
        $this->assertInstanceOf('Closure', $query());
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
