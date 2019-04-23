<?php
declare(strict_types=1);

namespace pulledbits\phuncql;


use function pulledbits\pdomock\createMockPDOCallback;

class PhuncqlTest extends \PHPUnit\Framework\TestCase
{
    public function testSanity_When_True_Expect_True() : void
    {
        $this->assertTrue(true);
    }

    final public function testConnect_When_ValidDSN_Expect_QueryExecutorClosure() : void {
        pdo::$links['mysql:host=localhost;dbname=testdb'] = createMockPDOCallback();
        pdo::$links['mysql:host=localhost;dbname=testdb']->callback(function(string $query, array $parameters) {
        });
        $querier = connect('mysql:host=localhost;dbname=testdb', function(\Error $error){});
        $this->assertInstanceOf('Closure', $querier);
        unset(pdo::$links['mysql:host=localhost;dbname=testdb']);
    }

    final public function testConnect_When_ConnectionError_Expect_LogTriggered() : void {
        $querier = connect('mysql:host=localhost;dbname=testdb', function(\Error $error) {
            $this->assertContains('Unable to connect', $error->getMessage());
        });
    }


    final public function testQuerier_When_Valid_Query_Expect_ResultSetIterator() : void {

        pdo::$links['mysql:host=localhost;dbname=testdb'] = createMockPDOCallback();
        pdo::$links['mysql:host=localhost;dbname=testdb']->callback(function(string $query, array $parameters) {
        });
        $querier = connect('mysql:host=localhost;dbname=testdb', function(\Error $error){});
        $query = $querier('SELECT * FROM test');
        $this->assertInstanceOf('Closure', $query());
        unset(pdo::$links['mysql:host=localhost;dbname=testdb']);
    }

}
