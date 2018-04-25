<?php
declare(strict_types=1);

namespace pulledbits\phuncql;


class PhuncqlTest extends \PHPUnit\Framework\TestCase
{
    public function testSanity_When_True_Expect_True()
    {
        $this->assertTrue(true);
    }

    public function testParseQueries_When_Callback_Expect_MappedCallbackReturnValueToRewindable() {
        $parseQuery = function (string $rawQuery) {
            return 1;
        };
        $queries = phuncql::parseQueries($parseQuery, 'SELECT col1, col2 FROM table');

        $this->assertEquals(1, $queries->current());
    }


}
