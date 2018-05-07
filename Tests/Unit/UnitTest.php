<?php
namespace ApacheSolrForTypo3\Solrmlt\Tests\Unit;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2010-2015 Timo Schmidt <timo.schmidt@dkd.de>
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

use ApacheSolrForTypo3\Solr\Tests\Unit\UnitTest as SolrUnitTest;

/**
 * Base class for all unit tests in the solrmlt project
 *
 * @author Timo Hund <timo.hund@dkd.de>
 * @package TYPO3
 * @subpackage solrmlt
 */
abstract class UnitTest extends SolrUnitTest
{

    /**
     * @param string $version
     */
    protected function skipInVersionBelow($version)
    {
        if (version_compare(TYPO3_branch, $version, '<')) {
            $this->markTestSkipped('This test requires at least version ' . $version);
        }
    }
}
