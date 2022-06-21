# Motivation

1. On larger and more complex sites, doing a full cache clear can lead to significant performance issues while things get rebuilt, when you often only need to rebuild one or two things.

2. It's often hard to remember exactly what service or command can clear/rebuild what, and methods to do so vary by name.

This module provides a plug-in manager, base plug-in, several commonly used plug-ins, along with Drush commands an a UI sub-module to invoke them as needed.

----

# Requirements

* Drupal 9 or newer

* PHP 8.0 or newer

* Drush 11 or newer

----

# Usage

Once installed, two Drush commands become available:

1. ```rebuilder:list``` - lists all available Rebuilder plug-ins.

2. ```rebuilder:run``` - runs a specified Rebuilder.

A *Rebuilder UI* sub-module is also provided that adds an administration form where individual Rebuilders can be invoked. Once the module is enabled, this can be found under:

>  Administration / Configuration / Development / Performance / Rebuilder

or the path: ```/admin/config/development/performance/rebuilder```

----

# Questions and answers

* *Can I write my own plug-ins?* Absolutely! In fact, that's one of the reasons this module is built around a plug-in manager rather than hard-coding it all. See the [Drupal Plug-in API documentation](https://www.drupal.org/docs/drupal-apis/plugin-api) for how to implement your own.

* *Why is this named Rebuilder and not Rebuild?* There's already a [Drush Rebuild project](https://www.drupal.org/project/rebuild) with the Composer name of ```drupal/rebuild```. Additionally, [the ```cache:rebuild``` Drush command](https://www.drush.org./latest/commands/cache_rebuild/) has an alias of ```rebuild```. Rebuilder was chosen as a name to avoid collision with these.
