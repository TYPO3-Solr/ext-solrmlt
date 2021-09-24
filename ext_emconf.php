<?php
$EM_CONF[$_EXTKEY] = [
    'title' => 'Apache Solr for TYPO3 - More Like This',
    'description' => 'More Like This',
    'version' => '10.0.0',
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
            'solr' => '11.1.0-',
            'typo3' => '10.4.10-',
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
