[![Latest Stable Version](https://poser.pugx.org/ionghitun/laravel-lumen-mysql-encryption/v/stable)](https://packagist.org/packages/ionghitun/laravel-lumen-mysql-encryption)
[![Build Status](https://travis-ci.com/ionghitun/laravel-lumen-mysql-encryption.svg?branch=master)](https://travis-ci.com/ionghitun/laravel-lumen-mysql-encryption)
[![Total Downloads](https://poser.pugx.org/ionghitun/laravel-lumen-mysql-encryption/downloads)](https://packagist.org/packages/ionghitun/laravel-lumen-mysql-encryption)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/ionghitun/laravel-lumen-mysql-encryption/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/ionghitun/laravel-lumen-mysql-encryption/?branch=master)
[![License](https://poser.pugx.org/ionghitun/laravel-lumen-mysql-encryption/license)](https://packagist.org/packages/ionghitun/laravel-lumen-mysql-encryption)

# Laravel Mysql Encryption

Database fields encryption in laravel and lumen for mysql databases with native search and anonymize data.

## Instalation notes

`$ composer require ionghitun/laravel-lumen-mysql-encryption`

## Dependencies

- php >= 8.0

## Documentation:

Service provider is automatically loaded for laravel, for lumen you need to add

    $app->register(IonGhitun\MysqlEncryption\MysqlEncryptionServiceProvider::class);

to your `bootstrap/app.php` file.

You need to add `ENCRYPTION_KEY` to your `.env` file, has to be 16 chars long.

Each of your Models should extend `IonGhitun\MysqlEncryption\Models\BaseModel` and for the fields you want to be encrypted you have to do the following:

- in migrations set field to `binary`
- add `$encrypted` to your model:

        /** @var array */
        protected $encrypted = [
            'name',
            'email'
        ];

You can use Validator on these fields with:

- unique_encrypted

        unique_encrypted:<table>,<field(optional)>

- exists_encrypted

        exists_encrypted:<table>,<field(optional)>

You cannot use basic where, orWhere, orderBy on encrypted fields so there are 5 predefined scopes that you can use as a replacer:

- whereEncrypted
- whereNotEncrypted
- orWhereEncrypted
- orWhereNotEncrypted
- orderByEncrypted

Possibility to anonymize data:

- set `$anonymizable` variable on your model, the data will be anonymize using https://github.com/fzaninotto/Faker, for all the types available check this package.

Example:

        //without extra parameters needed for randomDigit
        protected $anonymizable = [
            'age' => ['randomDigit']
        ];
        
        //with extra parameters needed for numberBetween
        protected $anonymizable = [
            'age' => ['numberBetween', '18','50']
        ];

- get your model instance and use anonymize method: `$user->anonymize();`

The method accepts a locale parameter, if you want to use faker with localization, the default locale can be set in `.env` file: `FAKER_LOCALE = 'en_US'`

If is not specified by any method above, the default Faker local will be used by default

Note: Model is not automatically saved!

_Happy coding!_
