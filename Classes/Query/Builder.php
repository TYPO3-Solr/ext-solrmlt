<?php
namespace ApacheSolrForTypo3\Solrmlt\Query;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2009-2012 Ingo Renner <ingo@typo3.org>
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

use ApacheSolrForTypo3\Solr\Site;
use ApacheSolrForTypo3\Solr\Util;
use ApacheSolrForTypo3\Solrmlt\Configuration\PluginConfiguration;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;
use TYPO3\CMS\Frontend\Plugin\AbstractPlugin;

/**
 * Builder class responsible to build an initialized more like this query.
 *
 * @author Timo Schmidt
 */
class Builder
{
    /**
     * Creates an intance of the Solrmlt Query.
     *
     * @return \ApacheSolrForTypo3\Solrmlt\Query\Query
     */
    protected function getMoreLikeThisQuery()
    {
        return GeneralUtility::makeInstance('ApacheSolrForTypo3\\Solrmlt\\Query\\Query');
    }

    /**
     * Builds an initialized query from the plugin configuration and frontend controller.
     *
     * @param PluginConfiguration $pluginConfiguration
     * @param TypoScriptFrontendController $TSFE
     * @return Query
     */
    public function build(PluginConfiguration $pluginConfiguration, TypoScriptFrontendController $TSFE)
    {
        /** @var $query \ApacheSolrForTypo3\Solrmlt\Query\Query */
        $query = $this->getMoreLikeThisQuery();
        $query->setQueryFields(array('id,title', 'score'));

        $query = $this->applyFrontendRestrictions($query, $TSFE);
        $query = $this->applyPluginConfiguration($query, $pluginConfiguration);
        $query = $this->applyQueryString($query, $pluginConfiguration, $TSFE);

        return $query;
    }

    /**
     * Applies the querystring, based on the configuration "QueryStringCreationType".
     *
     * @param Query $query
     * @param PluginConfiguration $pluginConfiguration
     * @param TypoScriptFrontendController $TSFE
     * @return Query
     */
    protected function applyQueryString(Query $query, PluginConfiguration $pluginConfiguration, TypoScriptFrontendController $TSFE)
    {
        switch ($pluginConfiguration->getQueryStringCreationType()) {
            case 'pagetitle':
            default:
                $queryString = $TSFE->page['title'];
                break;
        }

        $query->setQueryString($queryString);

        return $query;
    }

    /**
     * Applies the TSFE Group and SiteHash restrictions on the query.
     *
     * @param Query $query
     * @param TypoScriptFrontendController $TSFE
     * @return Query
     */
    protected function applyFrontendRestrictions(Query $query, TypoScriptFrontendController $TSFE)
    {
        $query->setUserAccessGroups(explode(',', $TSFE->gr_list));
        $query->setSiteHashFilter($this->getSiteHashFilterForTSFE($TSFE));

        return $query;
    }

    /**
     * Retrieves the value that should be used for the SiteHash filter.
     *
     * @param TypoScriptFrontendController $TSFE
     * @return string
     */
    protected function getSiteHashFilterForTSFE(TypoScriptFrontendController $TSFE)
    {
        return Site::getSiteByPageId($TSFE->id)->getDomain();
    }

    /**
     * Applies the settings of the plugin configuration on the created query.
     *
     * @param Query $query
     * @param PluginConfiguration $pluginConfiguration
     * @return Query
     */
    protected function applyPluginConfiguration(Query $query, PluginConfiguration $pluginConfiguration)
    {
        $query->setSimilarityFields($pluginConfiguration->getSimilarityFields());
        $query->setMinimumTermFrequency($pluginConfiguration->getMinTermFrequency());
        $query->setMinimumDocumentFrequency($pluginConfiguration->getMinDocumentFrequency());
        $query->setMinimumWordLength($pluginConfiguration->getMinWordLength());
        $query->setMaximumWordLength($pluginConfiguration->getMaxWordLength());
        $query->setMaximumQueryTerms($pluginConfiguration->getMaxQueryTerms());

        return $query;
    }
}
