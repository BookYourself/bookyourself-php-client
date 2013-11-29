BookYourself PHP Client
-----------------------
PHP client for BookYourself API

PHP OAuth 2.0 client specified for BookYourself and it's API paths

_**WARWING: this PHP Client is just beta version, it will evolve in future and for now is only for internal use**_

Dependences
-----------

* php cURL module
 * php5-curl is needed for communicatio between BYS and client library. It's used by httpfull library
* phar
 * we use httpful library to comunicate with BYS, which is stored in httpful.phar for lower size of repository.

Instalation
-----------

### Git instalation

* Clone this repo:

```
    git clone https://github.com/BookYourself/bookyourself-php-client.git
```

* copy `config_example.php` to `config.php` and add for you specific configuration
* include and use it from your code

### Instalation from ZIP archive

* Download [ZIP archive](https://github.com/BookYourself/bookyourself-php-client/archive/master.zip)
* unpack it to your project
* copy `config_example.php` to `config.php` and add for you specific configuration
* include and use it from your code

Configuration
-------------

All configuration for client is done in `config.php` file, which can be copied from `config_example.php`. 

Config file contain for now 5 configurable options:

| Config variable        | Value                             | Description   |
| ---------------------- | --------------------------------- | ------------- |
| **$BYS_client_id**     | your client id                    | ID of your id client, which was created with OAuth client |
| **$BYS_client_secret** | your client secret                | Secret of your client |
| **$BYS_redirect_uri**  | your client redirect uri          | One of redirect uris of client which use in oauth calling by this client |
| **$BYS_enviroment**    | "dev" <br>"test" <br>"production" | Enviroment to work in |
| **$BYS_language**      | **"en" - default** <br> "sk"      | Language to use in i-frames |

### $BYS_enviroment

Bys has 3 different enviroments:
* **dev** - used for development, very unstable, possibility of data lost, not uptime guaranted
* **test** - relatively stable for uptime, but repetively cleaned from data, because of that high posibility of data lost.
this enviroment is perfect for testing and preparing your client app before merge it to production
* **production** - production enviroment, stable, no data lost, minimal down time. But all data are live - wrong enviroment for testing and creating test users and providers, for this, you should use _**test**_.

### $BYS_language

Bys language change base url of iframes and oauth authentfication. It only change language specific part of url and that cause that language will be correct.

Possible languages for now:
* **en** - with alias com - at domain `.com` _(for test and production `com.` subdomain)_
* **sk** - at domain `.sk` (subdomain `sk.` for _test_ and _dev_

Next planned languages: cs, ru.

Feature requests and bugs
-------------------------

If something is not clear, is missing or don't work as it should, please create [Issue](https://github.com/BookYourself/bookyourself-php-client/issues/new) :-)

Example of usage
----------------

If you are interesting about examples of usage, look at [examples](https://github.com/BookYourself/bookyourself-php-client/tree/master/Simple). 

_For future, there will be another wiki page which will explain some usege of this PHP client, for now, examples have to be enought._
