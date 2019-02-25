<?php
$EM_CONF[$_EXTKEY] = [
    'title' => 'Apache Solr for TYPO3 - More Like This',
    'description' => 'More Like This',
    'version' => '3.0.0',
    'state' => 'stable',
    'category' => 'plugin',
    'author' => 'Ingo Renner, Timo Hund',
    'author_email' => 'ingo@typo3.org',
    'author_company' => 'dkd Internet Service GmbH',
    'module' => '',
    'uploadfolder' => 0,
    'createDirs' => '',
    'modify_tables' => '',
    'clearCacheOnLoad' => 0,
    'constraints' => [
        'depends' => [
            'solr' => '9.0.0-',
            'typo3' => '8.7.0-9.5.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
    'autoload' => [
        'psr-4' => [
            'ApacheSolrForTypo3\\Solrmlt\\' => 'Classes/',
            'ApacheSolrForTypo3\\Solrmlt\\Tests\\' => 'Tests/'
        ]
    ]
];
