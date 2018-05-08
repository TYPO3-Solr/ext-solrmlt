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

use ApacheSolrForTypo3\Solr\Domain\Search\Query\ParameterBuilder\QueryFields;
use ApacheSolrForTypo3\Solr\Domain\Search\Query\Query as SolrQuery;

/**
 * A query specialized to get documents similar to another
 *
 * @author    Ingo Renner <ingo@typo3.org>
 */
class Query extends SolrQuery
{

    protected $configuration;


        // configuration
    protected $similarityFields         = ['title', 'content'];

    /**
     * @var QueryFields
     */
    protected $queryFields              = null;

    protected $includeMatch             = false;
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
    public function __construct()
    {
        $this->queryFields = QueryFields::fromString('*,score');
        parent::__construct('');
        $this->setQueryType('mlt');
    }

    /**
     * Builds the additional query parameters "mlt.*" and retrieves them.
     *
     * @return array
     */
    public function getQueryParameters()
    {
        $moreLikeThisParameters = array(
            'mlt.fl'               => implode(',', $this->similarityFields),
            'mlt.qf'               => $this->queryFields->toString(','),
            'mlt.match.include'    => $this->includeMatch ? 'true' : 'false',
            'mlt.interestingTerms' => $this->interestingTerms,
            'mlt.mintf'            => $this->minimumTermFrequency,
            'mlt.mindf'            => $this->minimumDocumentFrequency,
            'mlt.minwl'            => $this->minimumWordLength,
            'mlt.maxwl'            => $this->maximumWordLength,
            'mlt.maxqt'            => $this->maximumQueryTerms
        );

        return array_merge($moreLikeThisParameters, $this->queryParametersContainer->toArray());
    }

    /**
     * Returns the fields that are used for similarity
     *
     * @return array
     */
    public function getSimilarityFields()
    {
        return $this->similarityFields;
    }

    /**
     * Used to set the fields that are used for similarity "mlt.fl".
     *
     * @param array $similarityFields
     */
    public function setSimilarityFields(array $similarityFields)
    {
        if (!empty($similarityFields)) {
            $this->similarityFields = $similarityFields;
        }
    }

    /**
     * Used to set the query fields that are used in "mlt.qf".
     *
     * @return array
     */
    public function getQueryFields()
    {
        return $this->queryFields;
    }

    /**
     * Used to set the query fields that are used for "mlt.qf".
     *
     * @param QueryFields $queryFields
     */
    public function setQueryFields(QueryFields $queryFields)
    {
        if (!empty($queryFields)) {
            $this->queryFields = $queryFields;
        }
    }

    /**
     * Indicates if the match itself should be included or not.
     * Used in "mlt.match.include".
     *
     * @return bool
     */
    public function getIncludeMatch()
    {
        return $this->includeMatch;
    }

    /**
     * Used to configure if the match itself should be included or not.
     * Used in "mlt.match.include"
     *
     * @param bool $includeMatch
     */
    public function setIncludeMatch($includeMatch)
    {
        $this->includeMatch = (boolean)$includeMatch;
    }

    /**
     * One of: "list", "details", "none" -- this will show what "interesting" terms are used for the MoreLikeThis query.
     * These are the top tf/idf terms. NOTE: if you select 'details', this shows you the term and boost
     * used for each term. Unless mlt.boost=true all terms will have boost=1.0
     *
     * Used in "mlt.interestingTerms"
     *
     * @return string
     */
    public function getInterestingTerms()
    {
        return $this->interestingTerms;
    }

    /**
     * One of: "list", "details", "none" -- this will show what "interesting" terms are used for the MoreLikeThis query.
     * These are the top tf/idf terms. NOTE: if you select 'details', this shows you the term and boost
     * used for each term. Unless mlt.boost=true all terms will have boost=1.0
     *
     * @param string $interestingTerms
     */
    public function setInterestingTerms($interestingTerms)
    {
        if (in_array($interestingTerms, ['list', 'details', 'none'])) {
            $this->interestingTerms = $interestingTerms;
        }
    }

    /**
     * Minimum Term Frequency - the frequency below which terms will be ignored in the source doc.
     *
     * Used to fill: 'mlt.mintf'
     *
     * @return int
     */
    public function getMinimumTermFrequency()
    {
        return $this->minimumTermFrequency;
    }

    /**
     * Minimum Term Frequency - the frequency below which terms will be ignored in the source doc.
     *
     * Used to fill: 'mlt.mintf'
     *
     * @param int $minimumTermFrequency
     */
    public function setMinimumTermFrequency($minimumTermFrequency)
    {
        $minimumTermFrequency = intval($minimumTermFrequency);

        if ($minimumTermFrequency > 0) {
            $this->minimumTermFrequency = $minimumTermFrequency;
        }
    }

    /**
     * Minimum Document Frequency - the frequency at which words will be ignored
     * which do not occur in at least this many docs.
     *
     * Used to fill: 'mlt.mindf'
     *
     * @return int
     */
    public function getMinimumDocumentFrequency()
    {
        return $this->minimumDocumentFrequency;
    }

    /**
     * Minimum Document Frequency - the frequency at which words will be ignored
     * which do not occur in at least this many docs.
     *
     * Used to fill: 'mlt.mindf'
     *
     * @param int $minimumDocumentFrequency
     */
    public function setMinimumDocumentFrequency($minimumDocumentFrequency)
    {
        $minimumDocumentFrequency = intval($minimumDocumentFrequency);

        if ($minimumDocumentFrequency > 0) {
            $this->minimumDocumentFrequency = $minimumDocumentFrequency;
        }
    }

    /**
     * Minimum word length below which words will be ignored.
     *
     * Used to fill: 'mlt.minwl'
     *
     * @return int
     */
    public function getMinimumWordLength()
    {
        return $this->minimumWordLength;
    }

    /**
     * Minimum word length below which words will be ignored.
     *
     * Used to fill: 'mlt.minwl'
     *
     * @param int $minimumWordLength
     */
    public function setMinimumWordLength($minimumWordLength)
    {
        $minimumWordLength = intval($minimumWordLength);

        if ($minimumWordLength > 0) {
            $this->minimumWordLength = $minimumWordLength;
        }
    }

    /**
     * Maximum word length above which words will be ignored.
     *
     * Used to fill: 'mlt.maxwl'
     * @return int
     */
    public function getMaximumWordLength()
    {
        return $this->maximumWordLength;
    }

    /**
     * Maximum word length above which words will be ignored.
     *
     * Used to fill: 'mlt.maxwl'
     * @param int $maximumWordLength
     */
    public function setMaximumWordLength($maximumWordLength)
    {
        $maximumWordLength = intval($maximumWordLength);

        if ($maximumWordLength > 0 && $maximumWordLength > $this->minimumWordLength) {
            $this->maximumWordLength = $maximumWordLength;
        }
    }

    /**
     * Maximum number of query terms that will be included in any generated query.
     *
     * Used to fill: 'mlt.maxqt'
     *
     * @return int
     */
    public function getMaximumQueryTerms()
    {
        return $this->maximumQueryTerms;
    }

    /**
     * Maximum number of query terms that will be included in any generated query.
     *
     * Used to fill: 'mlt.maxqt'
     *
     * @param int $maximumQueryTerms
     */
    public function setMaximumQueryTerms($maximumQueryTerms)
    {
        $maximumQueryTerms = intval($maximumQueryTerms);

        if ($maximumQueryTerms > 0) {
            $this->maximumQueryTerms = $maximumQueryTerms;
        }
    }
}
