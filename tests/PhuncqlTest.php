<?php
declare(strict_types=1);

namespace pulledbits\phuncql;


class PhuncqlTest extends \PHPUnit\Framework\TestCase
{
    public function testSanity_When_True_Expect_True() : void
    {
        $this->assertTrue(true);
    }

    public function testParseQueries_When_OneQuery_Expect_FirstElementToContainQuery() : void  {
        $queries = phuncql::parseQueries('SELECT col1, col2 FROM table');

        $this->assertEquals('SELECT col1, col2 FROM table', $queries[0]);
    }

    final public function testParseQueries_When_MulitpleQueries_Expect_ElementsToContainSucceedingQueries() : void {
        $queries = phuncql::parseQueries('SELECT col1, col2 FROM table;SELECT col3, col4 FROM table;SELECT col5, col6 FROM table');

        $this->assertEquals('SELECT col1, col2 FROM table', $queries[0]);
        $this->assertEquals('SELECT col3, col4 FROM table', $queries[1]);
        $this->assertEquals('SELECT col5, col6 FROM table', $queries[2]);
    }

    final public function testConnect_When_ValidDSN_Expect_QueryExecutorClosure() : void {
        $querier = phuncql::connect('mysql:host=localhost;dbname=testdb');
        $this->assertInstanceOf('Closure', $querier);
    }

    final public function testQuerier_When_Valid_Query_Expect_ResultSetIterator() : void {
        $querier = phuncql::connect('mysql:host=localhost;dbname=testdb');
        $results = $querier('SELECT * FROM test');
        $this->assertInstanceOf('Traversable', $results);
    }

}
