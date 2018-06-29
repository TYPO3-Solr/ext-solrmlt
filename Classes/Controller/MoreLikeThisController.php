<?php
namespace ApacheSolrForTypo3\Solrmlt\Controller;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use ApacheSolrForTypo3\Solr\ConnectionManager;
use ApacheSolrForTypo3\Solr\Search;
use ApacheSolrForTypo3\Solrmlt\Configuration\PluginConfiguration;
use ApacheSolrForTypo3\Solrmlt\Query\Builder;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Service\FlexFormService;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

/**
 * Class MoreLikeThisController
 *
 * @author Timo Hund <timo.hund@dkd.de>
 * @package ApacheSolrForTypo3\Solrmlt\Controller
 */
class MoreLikeThisController extends ActionController
{
    /**
     * @var Search
     */
    protected $search;

    /**
     * @var Builder
     */
    protected $queryBuilder;

    /**
     * @var PluginConfiguration
     */
    protected $pluginConfiguration;

    /**
     * @var TypoScriptFrontendController
     */
    protected $typoScriptFrontendController;

    /**
     * @var ContentObjectRenderer
     */
    private $contentObjectRenderer;

    /**
     * @param \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface $configurationManager
     * @return void
     */
    public function injectConfigurationManager(ConfigurationManagerInterface $configurationManager)
    {
        $this->configurationManager = $configurationManager;
        $this->contentObjectRenderer = $this->configurationManager->getContentObject();
    }

    /**
     * @return PluginConfiguration
     */
    protected function getPluginConfiguration()
    {
        if ($this->pluginConfiguration == null) {
            $flexFormData = $this->contentObjectRenderer->data['pi_flexform'];
            $flexFormService = GeneralUtility::makeInstance(FlexFormService::class);
            $flexFormArray  = $flexFormService->convertFlexFormContentToArray($flexFormData);
            $this->pluginConfiguration = GeneralUtility::makeInstance(PluginConfiguration::class,$flexFormArray);
        }

        return $this->pluginConfiguration;
    }

    /**
     * @return TypoScriptFrontendController
     */
    protected function getTSFE()
    {
        if ($this->typoScriptFrontendController == null) {
            $this->typoScriptFrontendController = $GLOBALS['TSFE'];
        }

        return $this->typoScriptFrontendController;
    }

    /**
     * @return Builder
     */
    protected function getQueryBuilder()
    {
        $this->queryBuilder = $this->queryBuilder ?? GeneralUtility::makeInstance(Builder::class);
        return $this->queryBuilder;
    }

    /**
     * @return Search
     */
    protected function getSearch()
    {
        if ($this->search === null) {
            /** @var \ApacheSolrForTypo3\Solr\ConnectionManager $solrConnection */
            $typoScriptFrontendController = $GLOBALS['TSFE'];
            $solrConnection = GeneralUtility::makeInstance(ConnectionManager::class)->getConnectionByPageId($typoScriptFrontendController->id, $typoScriptFrontendController->sys_language_uid, $typoScriptFrontendController->MP);
            $this->search = GeneralUtility::makeInstance(Search::class, $solrConnection);
        }

        return $this->search;
    }

    /**
     * Shows the results of the configured more like this query.
     *
     * @return void
     */
    public function indexAction()
    {
        $resultDocuments = [];
        $query = $this->getQueryBuilder()->build($this->getPluginConfiguration(), $this->getTSFE());
        $mltResults = $this->getSearch()->search($query, 1, $this->getPluginConfiguration()->getMaxItems());

        foreach ($mltResults->response->docs as $resultDocument) {
            $temporaryResultDocument = [];
            $availableFields = $resultDocument->getFieldNames();
            foreach ($availableFields as $fieldName) {
                $temporaryResultDocument[$fieldName] = $resultDocument->{$fieldName};
            }

            $resultDocuments[] = $temporaryResultDocument;
        }

        $this->view->assign('results', $resultDocuments);
    }
}
