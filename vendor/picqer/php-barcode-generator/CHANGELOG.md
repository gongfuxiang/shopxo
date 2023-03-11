# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [2.0.1] - 2020-01-28
### Fixed
- Removed special chars from filenames we use for test validation #94

## [2.0.0] - 2020-01-11
### Added
- Introduced Barcode and BarcodeBar classes to standardise generator output.
- Introduced methods to force use of GD or Imagick, see readme.
- Loads of new tests added, including tests on Github Actions.

### Changed
- Splitted all barcode types to different files.
- Refactored a lot of code for better readability, stricter checking, and to be more efficient.
- Merged JPG and PNG generators, because of duplicate code.

### Fixed
- Fixed a bug in Codabar generation 2d1128f5222d9368fc6151d2b51801ea29ba1052
- Do not draw multiple bars on the same position #74
- Do not try to draw barcodes for empty strings #42
- Fixed possible casting issue in Codabar #92

## [0.4.0] - 2019-12-31
### Added
- Added support for PHP 7.4, thanks to @pilif #80

## [0.3.0] - 2019-01-12
### Added
- SVG: Add viewBox attribute to allow svg scaling #68 by @cuchac
- Adjust CODE_128 to handle odd number of digits #55 by @richayles

### Fixed
- Bugfix update imagick function #51 by @Keinbockwurst

## [0.2.2] - 2017-09-28
### Added
- Raising exceptions if we cannot generate JPG or PNG because of missing libraries. Thanks @OskarStark

## [0.2.1] - 2016-10-24
### Fixed
- Bugfixes for wrong constant values.

## [0.2.0] - 2016-05-14
### Added
- This release adds exceptions to this package. Now it is easier to detect if the generated barcode is correct or not.

## [0.1.0] - 2015-08-13
### Added
- Everything. First release of this package.
