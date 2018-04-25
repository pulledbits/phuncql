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

        $this->assertEquals('SELECT col1, col2 FROM table', $queries[0]);
    }

    public function testParseQueries_When_MulitpleQueries_Expect_ElementsToContainSucceedingQueries() {
        $queries = phuncql::parseQueries('SELECT col1, col2 FROM table;SELECT col3, col4 FROM table;SELECT col5, col6 FROM table');

        $this->assertEquals('SELECT col1, col2 FROM table', $queries[0]);
        $this->assertEquals('SELECT col3, col4 FROM table', $queries[1]);
        $this->assertEquals('SELECT col5, col6 FROM table', $queries[2]);
    }

}
