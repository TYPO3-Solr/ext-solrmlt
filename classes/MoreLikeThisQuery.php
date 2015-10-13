<?php
namespace ApacheSolrForTypo3\Solrmlt;

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

use ApacheSolrForTypo3\Solr\Query;


/**
 * A query specialized to get documents similar to another
 *
 * @author    Ingo Renner <ingo@typo3.org>
 * @package    TYPO3
 * @subpackage    solr
 */
class MoreLikeThisQuery extends Query {

	protected $configuration;


		// configuration
	protected $similarityFields         = array('title', 'content');
	protected $queryFields              = array('*', 'score');
	protected $includeMatch             = FALSE;
	protected $interestingTerms         = 'details';
	protected $minimumTermFrequency     = 1;
	protected $minimumDocumentFrequency = 1;
	protected $minimumWordLength        = 3;
	protected $maximumWordLength        = 15;
	protected $maximumQueryTerms        = 20;

	/**
	 * Constructor
	 *
	 */
	public function __construct() {
		parent::__construct('');

		$this->configuration = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_solr.']['moreLikeThis.'];
		$this->setQueryType('mlt');
	}

	public function getQueryString() {
		return $this->queryString;
	}

	public function getQueryParameters() {
		$moreLikeThisParameters = array(
			'mlt.fl'               => implode(',', $this->similarityFields),
			'mlt.qf'               => implode(',', $this->queryFields),
			'mlt.match.include'    => $this->includeMatch ? 'true' : 'false',
			'mlt.interestingTerms' => $this->interestingTerms,
			'mlt.mintf'            => $this->minimumTermFrequency,
			'mlt.mindf'            => $this->minimumDocumentFrequency,
			'mlt.minwl'            => $this->minimumWordLength,
			'mlt.maxwl'            => $this->maximumWordLength,
			'mlt.maxqt'            => $this->maximumQueryTerms
		);

		return array_merge($moreLikeThisParameters, $this->queryParameters);
	}

	/**
	 *
	 * @return <type>
	 */
	public function getSimilarityFields() {
		return $this->similarityfields;
	}

	/**
	 *
	 * @param array $similarityFields
	 */
	public function setSimilarityFields(array $similarityFields) {
		if (!empty($similarityFields)) {
			$this->similarityFields = $similarityFields;
		}
	}

	public function getQueryFields() {
		return $this->queryFields;
	}

	public function setQueryFields(array $queryFields) {
		if (!empty($queryFields)) {
			$this->queryFields = $queryFields;
		}
	}

	public function getIncludeMatch() {
		return $this->includeMatch;
	}

	public function setIncludeMatch($includeMatch) {
		$this->includeMatch = (boolean)$includeMatch;
	}

	public function getInterestingTerms() {
		return $this->interestingTerms;
	}

	public function setInterestingTerms($interestingTerms) {
		if (in_array($interestingTerms, array('list', 'details', 'none'))) {
			$this->interestingTerms = $interestingTerms;
		}
	}

	public function getMinimumTermFrequency() {
		return $this->minimumTermFrequency;
	}

	public function setMinimumTermFrequency($minimumTermFrequency) {
		$minimumTermFrequency = intval($minimumTermFrequency);

		if ($minimumTermFrequency > 0) {
			$this->minimumTermFrequency = $minimumTermFrequency;
		}
	}

	public function getMinimumDocumentFrequency() {
		return $this->minimumDocumentFrequency;
	}

	public function setMinimumDocumentFrequency($minimumDocumentFrequency) {
		$minimumDocumentFrequency = intval($minimumDocumentFrequency);

		if ($minimumDocumentFrequency > 0) {
			$this->minimumDocumentFrequency = $minimumDocumentFrequency;
		}
	}

	public function getMinimumWordLength() {
		return $this->minimumWordLength;
	}

	public function setMinimumWordLength($minimumWordLength) {
		$minimumWordLength = intval($minimumWordLength);

		if ($minimumWordLength > 0) {
			$this->minimumWordLength = $minimumWordLength;
		}
	}

	public function getMaximumWordLength() {
		return $this->maximumWordLength;
	}

	public function setMaximumWordLength($maximumWordLength) {
		$maximumWordLength = intval($maximumWordLength);

		if ($maximumWordLength > 0 && $maximumWordLength > $this->minimumWordLength) {
			$this->maximumWordLength = $maximumWordLength;
		}
	}

	public function getMaximumQueryTerms() {
		return $this->maximumQueryTerms;
	}

	public function setMaximumQueryTerms($maximumQueryTerms) {
		$maximumQueryTerms = intval($maximumQueryTerms);

		if ($maximumQueryTerms > 0) {
			$this->maximumQueryTerms = $maximumQueryTerms;
		}
	}
}
