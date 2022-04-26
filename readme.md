***This module is still in active development and does not yet have a stable release but a 1.0 is planned to be released in the near future.***

----

# Motivation

1. On larger and more complex sites, doing a full cache clear can lead to significant performance issues while things get rebuilt, when you often only need to rebuild one or two things.

2. It's often hard to remember exactly what service or command can clear/rebuild what, and methods to do so vary by name.

This module provides a plug-in manager, base plug-in, and several commonly used plug-ins along with a Drush command to invoke them as needed.

----

# Requirements

* Drupal 9 or newer

* PHP 8.0 or newer

* Drush 11 or newer

----

# Questions and answers

* *Can I write my own plug-ins?* Absolutely! In fact, that's one of the reasons this module is built around a plug-in manager rather than hard-coding it all. See the [Drupal Plug-in API documentation](https://www.drupal.org/docs/drupal-apis/plugin-api) for how to implement your own.

* *Why is this named Rebuilder and not Rebuild?* There's already a [Drush Rebuild project](https://www.drupal.org/project/rebuild) with the Composer name of ```drupal/rebuild```. Additionally, [the ```cache:rebuild``` Drush command](https://www.drush.org./latest/commands/cache_rebuild/) has an alias of ```rebuild```. Rebuilder was chosen as a name to avoid collision with these.
