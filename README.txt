$Id$

Persistent URL for Drupal 6.x


Installation
------------

PURL can be installed like any other Drupal module -- place it in the modules
directory for your site and enable it (and its requirement, context) on the
admin/build/modules page.

PURL is an API module. It does absolutely nothing for the end user out of the
box without other modules that take advantage of its API.


Core concepts behind PURL
-------------------------

The mission of PURL is to provide an API for other modules to manipulate and
respond to portions of an HTTP request that are not captured by the core
Drupal menu system. By default, the Drupal menu system reacts to only one part
of a request: $_GET['q']. PURL aims to be a pluggable system for reacting to
parts of $GET['q'] but many others as well.

Some parts of a request that a PURL provider may respond to:

  Mozilla    http:// foobar.baz.com / group-a / node/5 ? foo=bar
    |                   |               |                   |
    |                   |               |                   |
  User agent     Subdomain/Domain     Prefix           Query string

Any modules using the PURL API must define one or more providers.

- A provider is a single concept for responding to or modifying a request.
  Examples: `spaces_og` activates group contexts, `views_modes` sets an active
  views style plugin, `locale` (if it were to use PURL) would activate the
  active language.

- A method is the means by which a provider is activated and modifies a
  request. The parts of the request, like user agent, prefix, query string,
  etc. are all exapmles of methods.

- A modifier is an id/value pair designated by a provider to trigger a response
  if found in the provider's method. Often modifiers map string aliases to an
  id, like ['mygroup', 5] (where 'mygroup' is the group's path and 5 is the
  group's node id). Other times, there is no reasonable mapping and a provider
  will want the literal value found in the request. These modifiers simply use
  the same string for the id and value, e.g. ['mozilla', 'mozilla']. 

One of PURLs goals is to make it possible for providers to be written to be
independent of the method that it uses. For example, `spaces_og` can activate
a group space when it finds a group identifier as a path prefix,
or a subdomain, or a specified query string, etc. depending on the method that
has been assigned to it by PURL.

The big picture is that PURL allows administrators to assign each provider a
method, and any time a valid modifier is found in a request for that given
method the provider is given a chance to respond via a callback function.

Example provider/method setup:

+---------------+--------------------------------+----------------------+
| Provider      | Method                         | Example modifier     |
+---------------+--------------------------------+----------------------+
| spaces_og     | Path prefix                    | ['mygroup', 5]       |
| views_modes   | Query string, key: 'viewstyle' | ['list', 'list']     |
| iphone_custom | Subdomain                      | ['iphone', 'iphone'] |
+---------------+--------------------------------+----------------------+

A sample URL which would trigger *all* of the providers above:

  http://iphone.foobar.com/mygroup/page-listing?viewstyle=list

**Responding**

When a modifier for a provider is found in a request, the provider's registered
callback is called with the ID for the given modifier. For example, in the
example above, the callback for provider `spaces_og` will be passed `5`, the id
corresponding to the `mygroup`, and it is then the provider's job to do whatever
it wants to do with that information. `spaces_og`, for example, will load node
`5` and set it as the active group context.

Depending on the method (e.g. any that involve $_GET['q']), PURL may remove the
modifier for the rest of the page load so that the request is passed cleanly to
the rest of the Drupal menu stack. While the original request above would have
had the path `mygroup/page-listing`, PURL will strip `mygroup` leaving the rest
of Drupal to think that the page's path is `page-listing`.

**Modifying**

Depending on the PURL method, outgoing requests may be automatically rewritten
to sustain the modifier found in the incoming request. In the example above,
any paths pushed through `url()` will be given the additional path prefix of
`mygroup`. Thus all links on the page and even requests like form autocomplete
AJAX calls will be prefixed.


Usage overview
--------------

@TODO


Maintainers
-----------
yhahn (Young Hahn)
jmiccolis (Jeff Miccolis)

Contributors
------------
Ian Ward
dmitrig01 (Dmitri Gaskin)
