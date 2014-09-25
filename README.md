# Teknik Web Services

---------------

Teknik is a suite of services with attractive and functional interfaces.

## Features
  * File Upload w/ encryption
  * Pastebin
  * Blogs
  * Git Repositories
  * Podcasts
  * Easy to use API
  * Server Statistics
  * Flexible installation and configuration

## Screenshots
[![File Upload Screenshot](https://cdn.teknik.io/default/img/screenshots/upload_screenshot_thumb.png)](https://cdn.teknik.io/default/img/screenshots/upload_screenshot.PNG)
[![Pastebin Screenshot](https://cdn.teknik.io/default/img/screenshots/paste_screenshot_thumb.png)](https://cdn.teknik.io/default/img/screenshots/paste_screenshot.PNG)
[![Blog Screenshot](https://cdn.teknik.io/default/img/screenshots/blog_screenshot_thumb.png)](https://cdn.teknik.io/default/img/screenshots/blog_screenshot.PNG)
[![Podcast Screenshot](https://cdn.teknik.io/default/img/screenshots/podcast_screenshot_thumb.png)](https://cdn.teknik.io/default/img/screenshots/podcast_screenshot.PNG)
[![Git Screenshot](https://cdn.teknik.io/default/img/screenshots/git_screenshot_thumb.png)](https://cdn.teknik.io/default/img/screenshots/git_screenshot.PNG)

You can also see a live demo [here](https://www.teknik.io).

## Requirements
In order to run Teknik on your server, you'll need:

  * IIS 7 with URL Rewrite module or Apache with mod_rewrite enabled (Requires conversion of web.config files)
  * PHP >= 5.4.14
  * MySQL
  * hMailServer (If running email as well)
  * Git >= 1.7.2
  * gitolite

## Installation
  * Clone the Teknik repository to your web root directory, or anywhere else you want to run Teknik from.

```
cd /var/www
git clone git://teknik.io/Teknik
```

  * Do not clone the development branch unless you want to run the latest code.  It may be unstable.
  * Create a database and import DB.sql to create the required tables.
  * Rename and Edit Configs
    - Teknik Configuration
      * Rename the `includes/config.php.default` file to `includes/config.php`.
      * Open up the `includes/config.php` and configure the site installation.
    - Git Viewer Configuration
      * Rename the `git/config.ini-example` file to `git/config.ini`.
      * Open up the `git/config.ini` and configure the git viewer.
    - Server Stats Configuration
      * Rename the `stats/phpsysinfo.ini.new` file to `stats/phpsysinfo.ini`.
      * Open up the `stats/phpsysinfo.ini` and configure the server stats.
    - Mail Web Interface Configuration
      * Rename the `mail/config/mail.inc.php.dist` file to `mail/config/mail.inc.php`.
      * Rename the `mail/config/db.inc.php.dist` file to `mail/config/db.inc.php`.
      * Open up the `mail/config/mail.inc.php` and configure the mail web interface settings.
      * Open up the `mail/config/db.inc.php` and configure the mail database settings.
  * If you are running IIS
    - Add the following virtual directories to every page (home, upload, git, etc...):
      * templates `/var/www/Teknik/templates`
      * includes `/var/www/Teknik/includes`
  * If you are running Apache
    - Create an Alias for each of the following:
      * templates `/var/www/Teknik/templates`
      * includes `/var/www/Teknik/includes`
  * Create the cache folders and give read/write permissions to your web server user:

```
cd /var/www/Teknik/templates/default
mkdir -p cache/js
chmod -R 777 cache

cd /var/www/Teknik/git
mkdir cache
chmod 777 cache
```

  * Create a local repo of the gitolite-admin repository and give it read/write permissions to the PHP script user.
    - In order to clone the repo, you will need to be added as an administrator within gitolite.  Refer to [Gitolite](http://gitolite.com/gitolite/) for information on how to set that up.

```
cd /var/Repositories/
git clone git@teknik.io:gitolite-admin
chown -R www_user:GitGroup /var/Repositories/gitolite-admin/*
chmod -R 750 /var/Repositories/gitolite-admin/*
```


**That's it**, installation complete! If you're having problems, let us know through the [Contact](https://contact.teknik.io/) page.

## Authors and contributors
  * [Chris Woodward](https://www.teknik.io) (Creator, developer)

## License
[BSD 3-Clause license](http://opensource.org/licenses/BSD-3-Clause)

## Development
You can view Teknik's [Development Branch](https://dev.teknik.io/) to see the current new features.  (It may not work, as it is a development branch)

## Contributing
If you are a developer, we need your help. Teknik is a young project and we have lot's of stuff to do. Some developers are contributing with new features, others with bug fixes. But you can also dedicate yourself to refactoring the current codebase and improving what we already have.  Any help you can give would be greatly appreciated!

## Further information
If you want to know more about the features of Teknik, check the [Help](https://help.teknik.io/) page. Also, if you're having problems with Teknik, let us know through the [Contact](https://contact.teknik.io/) page. Don't forget to give feedback and suggest new features! :)

