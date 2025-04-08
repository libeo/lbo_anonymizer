<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'lbo_anonymizer',
    'description' => 'Allow anonymisation of data with a command line',
    'version' => '13.0.0',
    'constraints' => [
        'depends' => [
            'typo3' => '13.0.0-13.4.99',
        ],
    ],
    'autoload' => [
        'psr-4' => [
            'Libeo\\LboAnonymizer\\' => 'Classes/',
        ],
    ],
];
