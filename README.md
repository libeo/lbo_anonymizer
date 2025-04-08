Use the php faker library to anonymize data in TYPO3 database.

## Usage

```bash
typo3cms lbo-anonymizer:anonymize
```

## Example of configuration

```php
$GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['lbo_anonymizer'] = [
    'DB' => [
        'Default' => [
            'sys_history' => [
                'operation' => 'truncate',
            ],
            'fe_users' => [
                'operation' => 'update',
                'fields' => [
                    'first_name' => [
                        'fakerFunction' => 'firstName',
                    ],
                    'last_name' => [
                        'fakerFunction' => 'lastName',
                    ],
                    'email' => [
                        'fakerFunction' => 'email',
                    ],
                    'lastlogin' => [
                        'fakerFunction' => 'unixTime',
                    ],
                    'address' => [
                        'fakerFunction' => 'address',
                    ],
                    'telephone' => [
                        'fakerFunction' => 'phoneNumber',
                    ],
                    'username' => [
                        'fakerFunction' => 'userName',
                    ],
                ]
            ],
        ]
    ],
];
```

