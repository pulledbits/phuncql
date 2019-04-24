<?php
declare(strict_types=1);

namespace pulledbits\phuncql;


use function pulledbits\pdomock\createMockPDOCallback;

class AsALibaryUser_IWantToConnectToASQLDatabase_SoThatICanInteractWithItTest extends \PHPUnit\Framework\TestCase
{
    public function testSanity() : void
    {
        $this->assertTrue(true);
    }

    final public function testConnect_WhenValidDSN_ExpectNoError() : void {
        pdo::$links['mysql:host=localhost;dbname=testdb'] = createMockPDOCallback();
        pdo::$links['mysql:host=localhost;dbname=testdb']->callback(function(string $query, array $parameters) {
        });
        $querier = connect('mysql:host=localhost;dbname=testdb', function(\Error $error){
            throw $error;
        });
        $this->assertInstanceOf('Closure', $querier);
        unset(pdo::$links['mysql:host=localhost;dbname=testdb']);
    }

    final public function testConnect_WhenInvalidDSN_ExpectError() : void {
        connect('mysql:host=localhost;dbname=testdb', function(\Error $error) {
            $this->assertContains('Unable to connect', $error->getMessage());
        });
    }


    final public function test() : void {

        pdo::$links['mysql:host=localhost;dbname=testdb'] = createMockPDOCallback();
        pdo::$links['mysql:host=localhost;dbname=testdb']->callback(function(string $query, array $parameters) {
        });
        $querier = connect('mysql:host=localhost;dbname=testdb', function(\Error $error){});
        $query = $querier('SELECT * FROM test');
        $this->assertInstanceOf('Closure', $query());
        unset(pdo::$links['mysql:host=localhost;dbname=testdb']);
    }

}
