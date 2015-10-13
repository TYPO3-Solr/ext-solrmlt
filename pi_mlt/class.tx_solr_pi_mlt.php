<?php
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

use ApacheSolrForTypo3\Solr\Plugin\PluginBase;
use ApacheSolrForTypo3\Solr\Site;
use ApacheSolrForTypo3\Solr\TemplateModifier;
use ApacheSolrForTypo3\Solrmlt\MoreLikeThisQuery;
use TYPO3\CMS\Core\Utility\GeneralUtility;


/**
 * Plugin 'Solr Search' for the 'solr' extension.
 *
 * @author	Ingo Renner <ingo@typo3.org>
 * @author	Timo Schmidt <timo.schmidt@aoemedia.de
 * @package	TYPO3
 * @subpackage	solr
 */
class tx_solr_pi_mlt extends PluginBase {

	/**
	 * Path to this script relative to the extension dir.
	 */
	public $scriptRelPath = 'pi_mlt/class.tx_solr_pi_mlt.php';


	/**
	 * Creates a moreLikeThis query and returns the Apache_Solr_Response for the
	 * query. The response is processed in the render method.
	 *
	 * @see classes/pluginbase/tx_solr_pluginbase_PluginBase#performAction()
	 * @return	Apache_Solr_Response	The Solr server's response
	 */
	protected function performAction() {
		$query = $this->getMoreLikeThisQuery();
		/* @var $query MoreLikeThisQuery */

		$query->setQueryString($this->getMoreLikeThisIdString());

		$response = $this->search->search($query, 0, $this->pi_getFFvalue(
			$this->cObj->data['pi_flexform'], 'maxItems'
		));

		$actionResult = $this->renderResponse($response);

		return $actionResult;
	}

	/**
	 * This method is used to create a moreLikeThis query and
	 * initializes it with the needed values.
	 *
	 * @return MoreLikeThisQuery
	 */
	protected function getMoreLikeThisQuery() {
		$query = NULL;

		if ($this->solrAvailable) {
			$query = GeneralUtility::makeInstance('ApacheSolrForTypo3\Solrmlt\MoreLikeThisQuery');

			$query->setUserAccessGroups(explode(',', $GLOBALS['TSFE']->gr_list));
			$query->setSiteHashFilter(Site::getSiteByPageId($GLOBALS['TSFE']->id)->getDomain());

			$query->setQueryFields(array('id,title', 'score'));

			$query->setSimilarityFields(GeneralUtility::trimExplode(
				',',
				$this->pi_getFFvalue(
					$this->cObj->data['pi_flexform'], 'similarityFields'
				),
				TRUE
			));
			$query->setMinimumTermFrequency($this->pi_getFFvalue(
				$this->cObj->data['pi_flexform'], 'minTermFrequency', 'sAdvanced'
			));
			$query->setMinimumDocumentFrequency($this->pi_getFFvalue(
				$this->cObj->data['pi_flexform'], 'minDocumentFrequency', 'sAdvanced'
			));
			$query->setMinimumWordLength($this->pi_getFFvalue(
				$this->cObj->data['pi_flexform'], 'minWordLength', 'sAdvanced'
			));
			$query->setMaximumWordLength($this->pi_getFFvalue(
				$this->cObj->data['pi_flexform'], 'maxWordLength', 'sAdvanced'
			));
			$query->setMaximumQueryTerms($this->pi_getFFvalue(
				$this->cObj->data['pi_flexform'], 'maxQueryTerms', 'sAdvanced'
			));
		}
		return $query;
	}

	/**
	 * Determines the moreLikeThis Id string to be used for the MLT query.
	 *
	 * @return	string	The string to be used to find similar documents
	 */
	protected function getMoreLikeThisIdString() {



			// FIXME allow to define q in a flexible way: either a GET
			// parameter, text, a URL or a document Id, for text see
			// http://wiki.apache.org/solr/MoreLikeThisHandler
			// (Examples at the bottom)
			// Maybe the parameter can be set by a hook?

		return $GLOBALS['TSFE']->page['title'];

#		$solrNntpGetParameters = t3lib_div::_GET('tx_solrnntp');
#		$solrNntpDocumentId = $solrNntpGetParameters['id'];

#		return 'id:' . $solrNntpDocumentId;
	}

	/**
	 * Renders the Solr response into a template.
	 *
	 * @param	Apache_Solr_Response	$mltResults
	 * @return	string	Rendered template
	 */
	protected function renderResponse(Apache_Solr_Response $mltResults) {
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
	 * @return string $actionResult Rendered plugin content
	 */
	protected function render($actionResult) {
		return $actionResult;
	}

	/**
	 * Returns the key which is used to read the templatefile from the typoscript setup.
	 *
	 * @see classes/pibase/tx_solr_pibase#getTemplateFileKey()
	 * @return string
	 */
	protected function getTemplateFileKey() {
		return 'moreLikeThis';
	}

	/**
	 * Returns the plugin key. Used in several base methods.
	 *
	 * @see classes/pibase/tx_solr_pibase#getPluginKey()
	 * @return string
	 */
	protected function getPluginKey() {
		return 'pi_mlt';
	}

	/**
	 * Returns the main subpart to work on.
	 *
	 * @see classes/pibase/tx_solr_pibase#getSubpart()
	 * @return string
	 */
	protected function getSubpart() {
		return 'solr_mlt';
	}
}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/solr/pi_mlt/class.tx_solr_pi_mlt.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/solr/pi_mlt/class.tx_solr_pi_mlt.php']);
}

?>
