<?php
declare(strict_types=1);

namespace pulledbits\phuncql;

use PHPUnit\Framework\TestCase;

class AsALibraryUser_IWantToGenerateQueriesInTheConnectionDialect_SoThatIDontHaveToWriteThemManuallyTest extends TestCase
{




    final public function test_WhenSelectQueryGenerated_ExpectAnSelectQuery() : void {
        $querier = connect('sqlite::memory:', function(\Error $error){});
        $query = $querier(function(string $dialect) : string {
            $this->assertEquals('sqlite', $dialect);
            return 'CREATE TABLE persons (col1 TEXT)';
        });
        $this->assertInstanceOf('Closure', $query());
    }


}
