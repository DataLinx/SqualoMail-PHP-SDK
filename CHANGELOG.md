# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

## [1.3.0] - 2022-10-21
### Added
- Implemented support for recipient tags for create, get and update requests
### Fixed
- Fixed unit test assertions for recipient custom attributes count

## [1.2.1] - 2022-07-11
### Changed
- Moved tests mapping to `autoload-dev`
- Added documentation and tests for recipient custom attributes

## [1.2.0] - 2022-06-02
### Added
- Added `GetListsDetails` request
- Added `accept` parameter to `SubscribeByEmail` and `UnsubscribeByEmail` requests
- Change `list_tags` parameter for `CreateList` request to array format, add test

## [1.1.0] - 2022-05-11
### Added
- Added `GetList` request

## [1.0.2] - 2022-05-05
### Added
- Add IP parameter to the `CreateRecipient` request
### Changed
- Improved tests

## [1.0.1] - 2022-05-05
### Fixed
- Fixed `UnsubscribeByEmail` request
- Other minor non-functional fixes

## [1.0.0] - 2022-04-28
### Added
- Initial library implementation
