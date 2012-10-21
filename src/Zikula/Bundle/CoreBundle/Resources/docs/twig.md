Templating DSL
==============

{{ zasset(path) }} where path can be '@<bundle>:<relative path>', e.g.
    `@Andreas08Theme:css/style.css`
    `@ZikulaUsersModule:js/style.js`
    `@Andreas08Theme:images/style.png`

    It will find the asset in order from CustomBundle, Theme, through to the requested path.

    or '<relative path to web folder>'

    `bundles/foomodule/css/style.css`

    Paths are always relative to a bundles `Resources/public/` folder.

