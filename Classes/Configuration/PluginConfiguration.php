<?php

namespace ApacheSolrForTypo3\Solrmlt\Configuration;

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

use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Configuration object to encapsulate the access of the plugin configuration
 *
 * @author Timo Hund <timo.hund@dkd.de>
 */
class PluginConfiguration
{

    /**
     * @var array
     */
    protected $pluginData;

    /**
     * PluginConfiguration constructor.
     * @param array $pluginData
     */
    public function __construct(array $pluginData)
    {
        $this->pluginData = $pluginData;
    }

    /**
     * Returns the flexform value of the plugin that was passed as context plugin.
     *
     * @param string $fieldName
     * @return mixed
     */
    protected function getFlexFormValueFromPluginContentObject(string $fieldName)
    {
        return $this->pluginData[$fieldName];
    }

    /**
     * Returns the maximum items to be shown.
     *
     * @return int
     */
    public function getMaxItems(): int
    {
        return (int)$this->getFlexFormValueFromPluginContentObject('maxItems');
    }

    /**
     * Returns the type how a query could be created.
     *
     * @see Configuration/FlexForms/MoreLikeThis.xml//queryStringCreationType
     * @return NULL|string
     */
    public function getQueryStringCreationType(): ?string
    {
        return $this->getFlexFormValueFromPluginContentObject('queryStringCreationType');
    }

    /**
     * Fields that should be used for similarity:
     *
     * Used to fill: 'mlt.fl'
     *
     * @return array
     */
    public function getSimilarityFields(): array
    {
        return GeneralUtility::trimExplode(
            ',',
            $this->getFlexFormValueFromPluginContentObject('similarityFields'),
            true
        );
    }

    /**
     * Minimum Term Frequency - the frequency below which terms will be ignored in the source doc.
     *
     * Used to fill: 'mlt.mintf'
     *
     * @return int
     */
    public function getMinTermFrequency(): int
    {
        return (int)$this->getFlexFormValueFromPluginContentObject('minTermFrequency');
    }

    /**
     * Minimum Document Frequency - the frequency at which words will be ignored
     * which do not occur in at least this many docs.
     *
     * Used to fill: 'mlt.mindf'
     *
     * @return int
     */
    public function getMinDocumentFrequency(): int
    {
        return (int)$this->getFlexFormValueFromPluginContentObject('minDocumentFrequency');
    }

    /**
     * Minimum word length below which words will be ignored.
     *
     * Used to fill: 'mlt.minwl'
     *
     * @return int
     */
    public function getMinWordLength(): int
    {
        return (int)$this->getFlexFormValueFromPluginContentObject('minWordLength');
    }

    /**
     * Maximum word length above which words will be ignored.
     *
     * Used to fill: 'mlt.maxwl'
     *
     * @return int
     */
    public function getMaxWordLength(): int
    {
        return (int)$this->getFlexFormValueFromPluginContentObject('maxWordLength');
    }

    /**
     * Maximum number of query terms that will be included in any generated query.
     *
     * Used to fill: 'mlt.maxqt'
     *
     * @return int
     */
    public function getMaxQueryTerms(): int
    {
        return (int)$this->getFlexFormValueFromPluginContentObject('maxQueryTerms');
    }
}
