# RSS

| `master` | `develop` |
|----------|-----------|
| [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/PersonalGalaxy/RSS/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/PersonalGalaxy/RSS/?branch=master) | [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/PersonalGalaxy/RSS/badges/quality-score.png?b=develop)](https://scrutinizer-ci.com/g/PersonalGalaxy/RSS/?branch=develop) |
| [![Code Coverage](https://scrutinizer-ci.com/g/PersonalGalaxy/RSS/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/PersonalGalaxy/RSS/?branch=master) | [![Code Coverage](https://scrutinizer-ci.com/g/PersonalGalaxy/RSS/badges/coverage.png?b=develop)](https://scrutinizer-ci.com/g/PersonalGalaxy/RSS/?branch=develop) |
| [![Build Status](https://scrutinizer-ci.com/g/PersonalGalaxy/RSS/badges/build.png?b=master)](https://scrutinizer-ci.com/g/PersonalGalaxy/RSS/build-status/master) | [![Build Status](https://scrutinizer-ci.com/g/PersonalGalaxy/RSS/badges/build.png?b=develop)](https://scrutinizer-ci.com/g/PersonalGalaxy/RSS/build-status/develop) |

Model to build a RSS client.

## Installation

```sh
composer require personal-galaxy/rss
```

## Usage

The only entry point to use the model are the [commands](src/Command), you should use a [command bus](https://github.com/innmind/commandbus) in order to bind the commands to their handler.

You also need to implement the repository [interfaces](src/Repository) in order to persist the subscriptions and articles.
