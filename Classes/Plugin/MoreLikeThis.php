<?php
namespace ApacheSolrForTypo3\Solrmlt\Plugin;

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

use ApacheSolrForTypo3\Solr\Plugin\PluginBase;
use ApacheSolrForTypo3\Solr\Site;
use ApacheSolrForTypo3\Solr\TemplateModifier;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

/**
 * Plugin 'Solr Search' for the 'solr' extension.
 *
 * @author Ingo Renner <ingo@typo3.org>
 * @author Timo Schmidt <timo.schmidt@aoemedia.de
 */
class MoreLikeThis extends PluginBase
{

    /**
     * Path to this script relative to the extension dir.
     */
    public $scriptRelPath = 'Classes/Plugin/MoreLikeThis.php';

    /**
     * @var \ApacheSolrForTypo3\Solrmlt\Query\Builder
     */
    protected $queryBuilder;

    /**
     * @var \ApacheSolrForTypo3\Solrmlt\Configuration\PluginConfiguration
     */
    protected $pluginConfiguration;

    /**
     * @var TypoScriptFrontendController
     */
    protected $typoScriptFrontendController;

    /**
     * @return \ApacheSolrForTypo3\Solrmlt\Configuration\PluginConfiguration
     */
    protected function getPluginConfiguration()
    {
        if ($this->pluginConfiguration == null) {
            $this->pluginConfiguration = GeneralUtility::makeInstance(
                'ApacheSolrForTypo3\Solrmlt\Configuration\PluginConfiguration',
                $this);
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
     * @return \ApacheSolrForTypo3\Solrmlt\Query\Builder
     */
    protected function getQueryBuilder()
    {
        if ($this->queryBuilder == null) {
            $this->queryBuilder = GeneralUtility::makeInstance('ApacheSolrForTypo3\Solrmlt\Query\Builder');
        }

        return $this->queryBuilder;
    }

    /**
     * Creates a moreLikeThis query and returns the Apache_Solr_Response for the
     * query. The response is processed in the render method.
     *
     * @see classes/pluginbase/tx_solr_pluginbase_PluginBase#performAction()
     * @return \Apache_Solr_Response The Solr server's response
     */
    protected function performAction()
    {
        $query = $this->getQueryBuilder()->build($this->getPluginConfiguration(), $this->getTSFE());

        $response = $this->search->search($query, 0, $this->getPluginConfiguration()->getMaxItems());
        $actionResult = $this->renderResponse($response);

        return $actionResult;
    }

    /**
     * Renders the Solr response into a template.
     *
     * @param \Apache_Solr_Response $mltResults
     * @return string Rendered template
     */
    protected function renderResponse(\Apache_Solr_Response $mltResults)
    {
        $resultDocuments = array();

        foreach ($mltResults->response->docs as $resultDocument) {
            $temporaryResultDocument = array();
            $availableFields = $resultDocument->getFieldNames();

            // TODO refactor: Move tx_solr_pi_results_ResultsCommand::processDocumentFieldsToArray() to a util class
            foreach ($availableFields as $fieldName) {
                $temporaryResultDocument[$fieldName] = $resultDocument->{$fieldName};
            }

            $resultDocuments[] = $temporaryResultDocument;
        }

        $this->template->addLoop('result_documents', 'result_document', $resultDocuments);

        if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['solr']['pi_mlt']['renderTemplate'])) {
            foreach ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['solr']['pi_mlt']['renderTemplate'] as $classReference) {
                $templateModifier = &GeneralUtility::getUserObj($classReference);

                if ($templateModifier instanceof TemplateModifier) {
                    $templateModifier->modifyTemplate($this->template);
                }
            }
        }

        return $this->template->render();
    }

    /**
     * This method executes the requested commands and applies the changes to
     * the template.
     *
     * @param string $actionResult
     * @return string Rendered plugin content
     */
    protected function render($actionResult)
    {
        return $actionResult;
    }

    /**
     * Returns the key which is used to read the template file from the typoscript setup.
     *
     * @see \ApacheSolrForTypo3\Solr\Plugin\PluginBase#getTemplateFileKey()
     * @return string
     */
    protected function getTemplateFileKey()
    {
        return 'moreLikeThis';
    }

    /**
     * Returns the plugin key. Used in several base methods.
     *
     * @see \ApacheSolrForTypo3\Solr\Plugin\PluginBase#getPluginKey()
     * @return string
     */
    protected function getPluginKey()
    {
        return 'pi_mlt';
    }

    /**
     * Returns the main subpart to work on.
     *
     * @see \ApacheSolrForTypo3\Solr\Plugin\PluginBase#getSubpart()
     * @return string
     */
    protected function getSubpart()
    {
        return 'solr_mlt';
    }
}
