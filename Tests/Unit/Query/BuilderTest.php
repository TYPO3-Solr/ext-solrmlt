<?php
namespace ApacheSolrForTypo3\Solrmlt\Tests\Unit\Query;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2009-2015 Ingo Renner <ingo@typo3.org>
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

use ApacheSolrForTypo3\Solrmlt\Tests\Unit\UnitTest;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Testcase to check if we could build a query with the QueryBuilder
 *
 * @author Timo Schmidt <timo.schmidt@dkd.de>
 * @package TYPO3
 * @subpackage solrmlt
 */
class BuilderTest extends UnitTest
{
    /**
     * @var \ApacheSolrForTypo3\Solrmlt\Query\Builder
     */
    protected $builder;

    /**
     * @return void
     */
    public function setUp()
    {
        $this->builder = $this->getMock('ApacheSolrForTypo3\\Solrmlt\\Query\\Builder', array('getSiteHashFilterForTSFE'));
    }

    /**
     * @test
     */
    public function canSetTheQueryStringFromPageTitle()
    {
        // we avoid the usage of the Sites::getSiteByPageId()->getDomain call an return a fake domain in our testcase
        $this->builder->expects($this->once())->method('getSiteHashFilterForTSFE')->will($this->returnValue('localhost'));

        $configurationMock = $this->getDumbMock('ApacheSolrForTypo3\Solrmlt\Configuration\PluginConfiguration');
        $configurationMock->expects($this->once())->method('getSimilarityFields')->will($this->returnValue(array('content')));

        $tsfeMock = $this->getDumbMock('TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController');
        $tsfeMock->page = array('title' => 'fake page title');

        $query = $this->builder->build($configurationMock, $tsfeMock);
        $this->assertSame('fake page title', $query->getQueryString(), 'Querybuilder did not assign expected querystring');
    }
}
