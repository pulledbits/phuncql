<?php
declare(strict_types=1);

namespace pulledbits\phuncql;

class PhuncqlTest extends \PHPUnit\Framework\TestCase
{
    public function testSanity_When_True_Expect_True()
    {
        $this->assertTrue(true);
    }

    public function testParseQueries_When_OneQuery_Expect_FirstElementToContainQuery() {
        $queries = phuncql::parseQueries('SELECT col1, col2 FROM table');
        $queries(function(string $query) {
            $this->assertEquals('SELECT col1, col2 FROM table', $query);
        });

    }

    public function testParseQueries_When_QueriesInvoked_Expect_CallbackBeingCalled() {
        $queries = phuncql::parseQueries('SELECT col1, col2 FROM table');

        $queries(function(string $query) {
            $this->assertEquals('SELECT col1, col2 FROM table', $query);
        });
    }

    public function testParseQueries_When_MulitpleQueries_Expect_ElementsToContainSucceedingQueries() {
        $queriesExpected = [
            'SELECT col1, col2 FROM table',
            'SELECT col3, col4 FROM table',
            'SELECT col5, col6 FROM table'
        ];

        $queries = phuncql::parseQueries(join(';', $queriesExpected));

        $queries(function(string $query) use (&$queriesExpected) {
            $this->assertEquals(array_shift($queriesExpected), $query);
        });

        $this->assertCount(0, $queriesExpected);
    }

}
