# Change Log - Laracogs
All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](http://semver.org/).
----

## [v2.5.1] - 2018-09-06

### Fixed
- Minor issue with case sensitive things

## [v2.5.0] - 2018-09-06

### Changed
- New support for Laravel 5.7
- Starter kit seeder improvements
- Better tests
- Cleaned up command notes to match docs better

## [v2.4.2] - 2018-09-03

### Added
- New Forge API admin panel

### Changed
- Updated and improved Starter kit elements

### Fixed
- Fixes for Queue UI
- Fixes for Activities UI

## [v2.4.1] - 2018-06-16

### Added
- New Queue managment admin

### Changed
- Improved log admin UI

### Fixed
- Issue with roles middleware
- Issue with coupon compatibility

## [v2.4.0] - 2018-03-19

### Changed
- Rebranding

## [v2.3.4] - 2017-11-20

### Added
- New admin logs view

## [v2.3.3] - 2017-09-20

### Fixed
- Issue with factories

## [v2.3.2] - 2017-09-18

### Added
- Better admin dashboard

### Changed
- Improved docs

## [v2.3.1] - 2017-09-01

### Fixed
- Version updates for better compatibility

## [v2.3.0] - 2017-08-31

### Changed
- Updated to support Laravel 5.5

## [v2.2.12] - 2017-05-13

### Changed
- Default admin to admin@example.org

### Fixed
- Fixes issue with features not yet defined

## [v2.2.11] - 2017-04-20

### Fixed
- Fixes helper for feature package

## [v2.2.10] - 2017-04-20

### Fixed
- Fixes helper for activity package

## [v2.2.9] - 2017-04-20

### Fixed
- Issues regarding activity tracking

## [v2.2.8] - 2017-04-17

### Changed
- Improved admin dashboard
- Improved billing support

## [v2.2.7] - 2017-04-09

### Fixed
- Issue with UserInviteRequest not working with admins

## [v2.2.6] - 2017-04-09

### Added
- New Activity tracking kit

## [v2.2.5] - 2017-04-02

### Fixed
- Some doc updates
- Repaired the starter seeder
- Fixed issue with auth routes

## [v2.2.4] - 2017-03-05

### Added
- Feature management kit

### Fixed
- Issue with TeamIntegrationTests

## [v2.2.3] - 2017-03-01

### Added
- New automatic redirect to designated dashboards for admins and users

## [v2.2.2] - 2017-02-25

### Fixed
- Fixed PCI compliance issues with the Billing

## [v2.2.1] - 2017-02-08

### Fixed
- Minor fixes

## [v2.2.0] - 2017-01-27

### Changed
- Laravel 5.4 compatibility

## [v2.1.11] - 2017-01-24

### Changed
- Set support to 5.3.*
- Added new compatibility guide

## [v2.1.10] - 2017-01-08

### Changed
- Better references for env variables

## [v2.1.9] - 2016-12-06

### Fixed
- Fixed some minor issues with the permissions in the starter kit

## [v2.1.8] - 2016-11-16

### Fixed
- Issue with user seeding

## [v2.1.7] - 2016-11-08

### Added
- New email based account activation

### Fixed
- Billing column name
- Notification components

## [v2.1.6] - 2016-10-31

### Added
- Brought over other packages for faster development of projects

### Changed
- Minor improvements to Bootstrap

## [v2.1.5] - 2016-10-21

### Fixed
- Fixes issue with Document generator with improper links

## [v2.1.4] - 2016-10-11

### Changed
- Added auto migrate on starter kit

### Fixed
- Minor corrections for billing card.js

## [v2.1.3] - 2016-10-03

### Fixed
- Improperly named file: UserRegisteredEmailListener.php and minor issue in billing test

## [v2.1.2] - 2016-09-29

### Changed
- Updated the Starter kit to work with Notifications

### Fixed
- API
-  Billing and some other features

## [v2.1.1] - 2016-09-06

### Changed
- Moved the CrudMaker to the new version without Repos

## [v2.1.0] - 2016-09-06

### Added
- Now uses events for User registration emails

### Changed
- Removed Repositories for less complex code out of the box

## [v2.0.1] - 2016-08-28

### Changed
- General template improvements

### Fixed
- composer issues

## [v2.0.0] - 2016-08-24

### Added
- Support for Laravel 5.3

### Changed
- Minor layout improvements

## [v1.9.37] - 2016-07-24

### Changed
- Moved several components to separate services

## [v1.9.36] - 2016-07-21

### Fixed
- Issue with improper routes in templates

## [v1.9.35] - 2016-07-14

### Added
- New progress bar for CRUD
- New apiOnly crud option

### Changed
- Refactoring for code quality
- Merged PRs

### Fixed
- Issue with test generation on cruds without schema

## [v1.9.34] - 2016-07-12

### Fixed
- Fixed some issues with semantic with upgrade to 2.2

## [v1.9.33] - 2016-07-11

### Added
- Starter kit now comes with permissions for roles

## [v1.9.32] - 2016-07-11
### Changed
- Code quality improvements

## [v1.9.31] - 2016-07-10

### Changed
- Minor improvements to calling CRUD generators
- Improved exception handling for commands
- Template improvements

### Fixed
- Docs on CRUD
- Minor schema builder fixes

## [v1.9.30] - 2016-07-07

### Changed
- Various improvements
- Code quality refactoring

### Fixed
- Default view fixes
- Better db transactions

## [v1.9.29] - 2016-07-04

### Fixed
- Repairs issue with checkboxes in form maker
- Adds for attribute for label making

## [v1.9.28] - 2016-06-28

### Fixed
- Issues with recursive directories

## [v1.9.27] - 2016-06-27

### Changed
- Lots of refactoring for easier maintenance
- General quality improvements

## [v1.9.26] - 2016-06-22

### Fixed
- Merged a pull request that repaired the User seeder

## [v1.9.25] - 2016-06-14

### Added
- New relationships handling for CRUD

## [v1.9.24] - 2016-06-11

### Changed
- Added controller directory creating

### Fixed
- Issue with namespace model in templates

## [v1.9.23] - 2016-06-08

### Fixed
- Corrects some docBlock comments
- Fixes issue with Semantic UI and roles in settings

## [v1.9.22] - 2016-06-07

### Added
- CRUD can now be service level only in case you do not want a full CRUD for the model

### Changed
- Tweaked the socialite requirements

## [v1.9.21] - 2016-05-28

### Removed
- Removed the web middleware for all routes in the kits due to Laravel 5.2+ conflict

## [v1.9.20] - 2016-05-26

### Added
- Ability to switch users

### Changed
- Minor improvements to UI

## [v1.9.19] - 2016-05-16

### Changed
- Improved the starter team and role pivot tables

## [v1.9.18] - 2016-05-16

### Added
- New roles middleware

### Changed
- Improved starter kit and notification security with form driven deletes
- Improved template security with form driven deletes

### Fixed
- Minor typos

## [v1.9.17] - 2016-05-13

### Added
- New Roles management for admins
- More unit tests

### Changed
- Improved auth on user requests
- Naming in admin user controller
- Unit test code style

## [v1.9.16] - 2016-05-13

### Changed
- Domain driven structure for user service in relation to teams

### Fixed
- Improvements to readme
-  Improvements to command descriptions
-  Fixed some unit test issues

## [v1.9.15] - 2016-05-10

### Fixed
- Added messages for basic crud templates
- Improved docs for semantic

## [v1.9.14] - 2016-05-06

### Added
- All new changelog for continuing growth

### Changed
- Improved Crypto enhancing the security measures
