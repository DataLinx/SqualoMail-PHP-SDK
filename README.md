# PHP SDK for the SqualoMail API

![Packagist PHP Version Support](https://img.shields.io/packagist/php-v/datalinx/squalomail-php-sdk)
![Packagist Version](https://img.shields.io/packagist/v/datalinx/squalomail-php-sdk)
![Packagist Downloads](https://img.shields.io/packagist/dt/datalinx/squalomail-php-sdk)
[![Tests](https://github.com/DataLinx/SqualoMail-PHP-SDK/actions/workflows/tests.yml/badge.svg)](https://github.com/DataLinx/SqualoMail-PHP-SDK/actions/workflows/tests.yml)
[![Conventional Commits](https://img.shields.io/badge/Conventional%20Commits-1.0.0-%23FE5196?logo=conventionalcommits&logoColor=white)](https://conventionalcommits.org)
![Packagist License](https://img.shields.io/packagist/l/datalinx/squalomail-php-sdk)


## About
**Disclaimer: This is not an official SDK.**

With this package you can use the SqualoMail API in your PHP project. 

See the changelog [here](CHANGELOG.md).

## Requirements
- PHP >= 7.4
- CURL and JSON PHP extensions
- Ubuntu is tested and supported, but should also work on Windows

## Installing
Download it with composer: 
```shell
composer require datalinx/squalomail-php-sdk
````

## Usage
```php
$api = new API('your API key here');

$request = new CreateRecipient($api);
$request->email = 'john@example.com';
$request->name = 'John';
$request->surname = 'Doe';

try {
    $response = $request->send();
    
    // Subscribed, see the CreateRecipientResponse class for details
    echo $response->getRecipient()->id;
}
catch (Exception $exception) {
    echo 'Oops, something went wrong: '. $exception->getMessage();
}

```

### Requests implementation
All API endpoints are specified [here](https://api.squalomail.com/v1/help).  
However, only the following are implemented in this package currently.

| Request               | Notes                                                                                                                                                                                                                                                                                                                                                                  |
|-----------------------|------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| Create recipient      |                                                                                                                                                                                                                                                                                                                                                                        |
| Get recipient         |                                                                                                                                                                                                                                                                                                                                                                        |
| Get recipient's lists | See the `GetRecipientLists` class docblock for explanation.                                                                                                                                                                                                                                                                                                            |
| Update recipient      | You can provide either the recipient ID or the email. If you provide both, the email address for the matching recipient ID will be updated.                                                                                                                                                                                                                            |
| Delete recipient      | You can delete recipients only by ID, so fetch the ID with a `GetRecipient` request first.                                                                                                                                                                                                                                                                             |
| Create list           |                                                                                                                                                                                                                                                                                                                                                                        |
| Delete list           |                                                                                                                                                                                                                                                                                                                                                                        |
| Get list              | See the `GetList::send()` method docblock for the response data                                                                                                                                                                                                                                                                                                        |
| Get list recipients   | Does not support pagination                                                                                                                                                                                                                                                                                                                                            |
| Get lists details     |                                                                                                                                                                                                                                                                                                                                                                        |
| Subscribe by email    | This works only for recipients that are already created. If re-subscribing, also set the "accept" parameter to "true".                                                                                                                                                                                                                                                 |
| Unsubscribe by email  | The "accept" attribute on the recipient is updated considering how you configured it in SqualoMail settings (Slovenian: "Po kliku na povezavo za odjavo naj prejemnika odjavi od"). You can also send a boolean here to force a change, but it will be ignored when the setting is set to the third option (Slovenian: "Prejemanja katerihkoli sporočil v prihodnje"). |

## Contributing
If you have some suggestions how to make this package better, please open an issue or even better, submit a pull request.

Should you want to contribute, please see the development guidelines in the [DataLinx PHP package template](https://github.com/DataLinx/php-package-template).

### Changelog
All notable changes to this project are automatically documented in the [CHANGELOG.md](CHANGELOG.md) file using the release workflow, based on the [release-please](https://github.com/googleapis/release-please) GitHub action.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

For all this to work, commit messages must follow the [Conventional commits](https://www.conventionalcommits.org/) specification, which is also enforced by a Git hook.

### Developer documentation
* [Official SqualoMail API documentation](https://www.squalomail.com/sl/podpora/sistemski-api/) (in Slovenian and Italian only)
* [Endpoint list](https://api.squalomail.com/v1/help)
