Templating DSL
==============

Assets
------
{{ zasset(path) }} where path can be '@<bundle>:<relative path>', e.g.
    `@Andreas08Theme:css/style.css`
    `@ZikulaUsersModule:js/style.js`
    `@Andreas08Theme:images/style.png`

    It will find the asset in order from CustomBundle, Theme, through to the requested path.

    or '<relative path to web folder>'

    `bundles/foomodule/css/style.css`

    Paths are always relative to a bundles `Resources/public/` folder.

CSS and JS assets
-----------------

    {{ css.add(resource) }}
    {{ js.add(resource) }}

While discouraged, it is possible to use

    {{ css.set(key, value) }}
    {{ js.set(key, value) }}

Page Variables
--------------

The following are available for commucating variables within templates.

    {{ pagevar.get(name) }}
    {{ themevar.get(name) }}
    {{ meta.get(name) }}

Each of the above also have setters so you may use `.set(key, value)`




