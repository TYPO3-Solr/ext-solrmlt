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
use ApacheSolrForTypo3\Solr\System\Solr\Document\Document;
use ApacheSolrForTypo3\Solrmlt\Configuration\PluginConfiguration;
use ApacheSolrForTypo3\Solrmlt\Query\Builder;
use Exception;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Core\Service\FlexFormService;
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
     * @param ConfigurationManagerInterface $configurationManager
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
    protected function getPluginConfiguration(): PluginConfiguration
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
    protected function getTSFE(): TypoScriptFrontendController
    {
        if ($this->typoScriptFrontendController == null) {
            $this->typoScriptFrontendController = $GLOBALS['TSFE'];
        }

        return $this->typoScriptFrontendController;
    }

    /**
     * @return Builder
     */
    protected function getQueryBuilder(): Builder
    {
        $this->queryBuilder = $this->queryBuilder ?? GeneralUtility::makeInstance(Builder::class);
        return $this->queryBuilder;
    }

    /**
     * @return Search
     */
    protected function getSearch(): Search
    {
        if ($this->search === null) {
            /** @var ConnectionManager $solrConnection */
            $typoScriptFrontendController = $GLOBALS['TSFE'];
            $context = GeneralUtility::makeInstance(Context::class);
            $languageUid = (int)$context->getPropertyFromAspect('language', 'id');
            $solrConnection = GeneralUtility::makeInstance(ConnectionManager::class)->getConnectionByPageId($typoScriptFrontendController->id, $languageUid, $typoScriptFrontendController->MP);
            $this->search = GeneralUtility::makeInstance(Search::class, $solrConnection);
        }

        return $this->search;
    }

    /**
     * Shows the results of the configured more like this query.
     *
     * @return void
     * @throws Exception
     */
    public function indexAction()
    {
        $resultDocuments = [];
        $query = $this->getQueryBuilder()->build($this->getPluginConfiguration(), $this->getTSFE());

        // we skip the first item since the is the most equals, so the page itself
        $mltResults = $this->getSearch()->search($query, 1, $this->getPluginConfiguration()->getMaxItems());

        foreach ($mltResults->response->docs as $resultDocument) {
            /** @var $resultDocument Document */
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