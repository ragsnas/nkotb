## Installation
**Composer-Installation**
    $ composer require friendsofsymfony/user-bundle
    $ composer install
or
    $ php composer.phar composer require friendsofsymfony/user-bundle
    $ php composer.phar install

**NPM**
    $ npm install -g gulp bower
    $ npm install

**Bower**
    $ bower install

**Assets-Deployment**
    $ gulp deploy

**ASSets watch**
    $ gulp watch

## Bundles

- **[FOSUserBundle](https://github.com/FriendsOfSymfony/FOSUserBundle):**
- **[DoctrineFixturesBundle](https://github.com/doctrine/DoctrineFixturesBundle):**

## Tools
- bower (_"app/Resources/bower"_)
- gulp (Tasks: deploy, styles, scripts, watch)
- [EditorConfig](http://editorconfig.org) ([PHPStorm Plugin](https://plugins.jetbrains.com/plugin/7294))

## Bower-Pakete
- [Bootstrap 3.3.*](http://getbootstrap.com/)
- [Font Awesome 4.3.*](http://fortawesome.github.io/Font-Awesome/icons/)
- [jQuery 2.1.*](http://jquery.com)

## Features
- base.html.twig Layout mit Assets & Flashbag-Messages (in _app/Resources/views/base.html.twig_)
- Twig-Extension-Template (in _src/AppBundle/Twig/Extension.php_)
- Exception-Error-Page-Template (in _app/Resources/TwigBundle/views/Exception/error.html.twig_)
- Translation-Templates (in _app/Resources/translations/_)
- Sf 2.6 Bootstrap3-Form-Layout aktiviert (in _app/config/config.yml_)
- Assetic-Controller & -Bundle entfernt (weil wir bower & grunt nutzen)
- parameters.yml mit "mailer_port" für Mailcatcher-Setup
- Bootstrap-Glyphicons aus dem Bootstrap-CSS entfernt, weil wir lieber Font Awesome nutzen! (in _app/Resources/less/bootstrap.less_)
- Bessere Lesbarkeit von Twig's {{ dump(object) }} (in _app/Resources/less/main.less_, "sf-dump"-CSS-Klasse)

# Gulp
## deploy-Task

**CSS:**

- Die _app/Resources/less/main.less_ wird erst nach _app/Resources/css/less.css_ kompliert und anschließend nach _web/css/main.css_ & _web/css/main.min.css_ kopiert.
- Die _app/Resources/sass/main.scss wird erst nach _app/Resources/css/sass.css_ kompliert und anschließend nach _web/css/main.css_ & _web/css/main.min.css_ kopiert.
- Die _app/Resources/less/main.less_ importiert die Bootstrap-Dateien.
- Die _app/Resources/sass/main.scss importiert die Font-Awesome-Dateien.
- Die _app/Resources/less/bootstrap.less_ ist eine Kopie der von Bootstrap gelieferten _bootstrap.less_, um die Glyphicons auskommentieren zu können.

**JavaScript:**

- Alle _app/Resources/js/*.js_-Dateien werden nach _web/js/main.js_ & _web/js/main.min.js_ kopiert.
- Das jQuery- und Bootstrap-JavaScript wird automatisch vor die _app/Resources/js/*.js_-Datei(en) per gulp-concat (in _Gulpfile.js_) in die _main.js_ eingefügt.