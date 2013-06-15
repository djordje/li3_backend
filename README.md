#li3_backend

### Backend for Lithium based applications

This library aim to enable consistent view for backend of your application.
You can have `partial-component` (for example user registration, login etc.) and
`backend-component` views for application parts that should be available to someone with access to
backend.
Also this library should make it easier to use someone library that relies on `li3_backend`, because
it will already have all dependencies and logic to override it in your application to fit your needs.

---

[![Build Status](https://travis-ci.org/djordje/li3_backend.png?branch=master)](https://travis-ci.org/djordje/li3_backend)

## Table of content:

* **[Instalation](#installation)**
* **[Included assets](#included-assets)**
* **[Usage:](#usage)**
  * [Bootstrap](#bootstrap)
  * [Routes](#routes)
  * [Controller](#controller)
  * [Helper](#helper)
  * [Model](#model)
  * [Library elements](#library-elements-left-column-menus)
  * [Template overriding](#template-overriding)
  * [Speed up loading or override assets](#speed-up-loading-or-override-assets)

## Installation

**1a.** You can install it trough composer:
```json
{
    "require": {
        "djordje/li3_backend": "dev-master"
    }
}
```

**1b.** Or you can clone git repo to any of your libraries dir:
```
cd libraries
git clone git://github.com/djordje/li3_backend.git
```

**2.** Add it to bottom of your app's `bootstrap/libraries.php` file (you can optionally pass url
prefix to library options):
```php
// backend routes with default prefix `backend`
Libraries::add('li3_backend');
// or pass custom backend url prefix
// backend routes with custom prefix `admin`
Libraries::add('li3_backend', array('urlPrefix' => 'admin'));
// or pass app name to be defined in li3_backend bootstrap
Libraries::add('li3_backend', array('appName' => 'My app'));
```

## Included assets

* **Libraries**
* **[jQuery](https://github.com/jquery/jquery) v1.10.1**

For now jQuery 1.x to support IE before v9. This enables easier integration of custom or existing
jQuery plugins to backend.

* **[Twitter Bootstrap](https://github.com/twitter/bootstrap) v2.3.2**

This library includes complete Twitter Bootstrap (CSS, JS and images for icons) to enable consistent
styles for all backend view, and enable easier development.

* **[Google Code Prettify](https://code.google.com/p/google-code-prettify/) v4-Mar-2013**

Includes JS and customized CSS to fit with Twitter Bootstrap. Enable easier code representation if
 we need to show something in backend.

* **Additional**
* **confirm-action.js**

If loaded require you to confirm any action on elements that have `data-confirm` propery and show
value as message. Loaded by default to warn user before doing some dangerous actions.

## Usage

#### Bootstrap

If not defined constant `LITHIUM_APP_NAME` define it as `'Lithium app'`. This will be used in
navbar. For `'partial-component'` and with `' backend'` sufix for `'backend-component'` to enable
user to see current location.
You can specify app name in library options to, pass key `appName` with desired name and it will be
used in this bootstrap to define `LITHIUM_APP_NAME` if not defined.

It adds rule for backend routing action name, and call `config/backend_bootstrap.php` for all
libraries that have it if detect backend route. This enables you to add custom bootstrap for backend
and to add links to `NavBar` model.

#### Routes

Add route to enable access to assets packed within libraries webroot and backend routing prefix.
You can customize backend routes prefix trough library options, explained in [installation](#installation).

Route to fetch assets use this pattern: `/assets/<library without li3_ prefix>/<args>`, for
example `/assets/backend/css/bootstrap.css` will route to `li3_backend/webroot/css/bootstrap.css`.

You can speed up or override included assets see:
*[speed up loading or override assets](#speed_up_loading_or_override_assets)*

#### Controller

All controllers that use this library should inherit from
`\li3_backend\extensions\controller\ComponentController` logic for templates, layouts and elements loading.

**ComponentController** have `$_viewAs` param which you can override in your controller to specify
desired backend view for all controller actions.

**ComponentController** have `_viewAs()` method which you can use inside single action to specify
desired view. This method accept one param and it accept same value as `$_viewAs` property.

```php
public function login() {
	$this->_vieweAs('partial-component');
}
```

**Valid values are:**

* `'partial-component'` if you want to make something that can be accessed by users that do not
have backend access, for example login  or user registration pages.
* `'backend-component'` if you make something that should load backend template (includes backend
links in navbar), for example page with user management options.

By default controller that inherit from `ComponentController` will use your `'default'` layout and
render paths for all actions except actions prefixed with `backend_` witch automatically reacts as
`backend-component`.

#### Helper

This library provide backend helper `\li3_backend\extensions\helper\Backend` that extends `Html`
helper and brings `nav()` and `dropdown()` methods that generates proper markup for styling with
`Twitter Bootstrap`.

You can use it in your template this way: `$this->backend->{method}`.

**nav()** accept tree params, same as `Html::link()`: `title`, `url` and `options`. It render
`<a />` wrapped in `<li />` and adds `active` class to `<li />` if current url is url of `<a />`.

*See doc block in class to find out more.*

**dropdown()** Generate dropdown from array that should have `'title'` and `'links'`, and optionally
`'icon'` keys.

*See doc block in class to find out more.*

#### Model

We use `\li3_backend\models\NavBar` as container for adding links to backend navbar.

Basically in most cases you'll add just one link to components menu to enable access to your library:

```php

\li3_backend\models\NavBar::addBackendLink(array(
	'title' => 'Blog posts',
	'url' => array('li3_something.Controller::action', 'backend' => true)
));

```

But you should *see doc block in class to find out more*, how to add new dropdown
with links insted of adding links under `Components` dropdown, or how to add link directly to navbar.

You can add backend home link, if you want:

```php

\li3_backend\models\NavBar::addBackendHome(array(
	'li3_something.Controller::action', 'backend' => true
));

```

#### Library elements (left column menus)

By default `'parital'` and `'backend'` layout loads `'left_column'` element that generates left
column menu for current component by loading elements if exists in this order:

* **For currently active controller**
* `<controller>/component`
* `<controller>/<layout>_component` - backend or partial
* **General component menu**
* `component`
* `<layout>_component` - backend or partial

#### Template overriding

`ComponentController` have render paths setup that enable easy template overriding in you `app`.

This feature enables us to change views for some library that use `li3_backend` without
changing original views, and ensure secure library updated without worries that something
we've customized will be overwritten.

This is list of paths arranged by priorities:

* _template_
  * `<app>/views/<library>/<controller>/<template>.<type>.php`
  * `<current library>/views/<controller>/<template>.<type>.php`
* _layout_
  * `<app>/views/layouts/backend/<layout>.<type>.php`
  * `<li3_backend>/views/layouts/<layout>.<type>.php`
* _element_
  * `<app>/views/elements/<library>/<template>.<type>.php`
  * `<current library>/views/elements/<template>.<type>.php`
  * `<li3_backend>/views/elements/<template>.<type>.php`

<app> is your LITHIUM_APP_PATH
<current library> is path to currently active library
<library> is name of currently active library
<li3_backend> is path to `li3_backend` library

**Example**

Add `backend.html.php` to `<app>/views/layouts/backend` and you'll override `li3_backend's`
default layout for `backend-component` setup.

#### Speed up loading or override assets

Because we use PHP to return public unaccessable assets stored in `li3_backend` or any other `li3`
library we spent server time.

We can fix this by adding correct directory structure and files to are app's webtoot. You have to
make file path same as `assets` url. For example if you add to you app's webroot
`assets/backend/css/bootstrap.css` all backend pages will load this file as static file, because
`.htaccess` configuration doesn't rewrite urls that point to existing file. Otherwise it will fetch
file trough our custom route.

You can use same technique to override `assets` of some library.