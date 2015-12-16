<?php
$EM_CONF[$_EXTKEY] = array(
    'title' => 'Apache Solr for TYPO3 - More Like This',
    'description' => 'More Like This',
    'version' => '1.1.0-dev',
    'state' => 'stable',
    'category' => 'plugin',
    'author' => 'Ingo Renner',
    'author_email' => 'ingo@typo3.org',
    'author_company' => 'dkd Internet Service GmbH',
    'module' => '',
    'uploadfolder' => 0,
    'createDirs' => '',
    'modify_tables' => '',
    'clearCacheOnLoad' => 0,
    'constraints' => array(
        'depends' => array(
            'solr' => '3.1.0-',
            'typo3' => '6.2.0-7.99.99',
        ),
        'conflicts' => array(
        ),
        'suggests' => array(
        ),
    ),
    'autoload' => array(
        'psr-4' => array(
            'ApacheSolrForTypo3\\Solrmlt\\' => 'Classes/',
            'ApacheSolrForTypo3\\Solrmlt\\Tests\\' => 'Tests/'
        )
    ),
    '_md5_values_when_last_written' => '',
);
