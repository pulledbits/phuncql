<?php
declare(strict_types=1);

namespace pulledbits\phuncql;

use PHPUnit\Framework\TestCase;

class AsALibraryUser_IWantToGenerateQueriesInTheConnectionDialect_SoThatIDontHaveToWriteThemManuallyTest extends TestCase
{
    final public function test_WhenQueryFunctionPassed_ExpectDialectedExtractedFromDSN() : void {
        $querier = connect('sqlite::memory:', function(\Error $error){});
        $querier(function(string $dialect) : string {
            $this->assertEquals('sqlite', $dialect);
            return 'CREATE TABLE persons (col1 TEXT)';
        });
    }


}
