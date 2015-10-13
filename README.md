Context Testing Module for Drupal 8
===

This is a very simple module that does exactly two things:

* It creates a "context provider" service that supplies the author of the node at the current URL.
* It supplies a simple Context plugin that returns true if a supplied user is the author of a node.

These are intended to help test some issues I'm involved with for the nearly finished Drupal 8.0 release.

