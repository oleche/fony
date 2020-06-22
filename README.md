# fony-php
A Rapid development PHP API framework

Welcome to Fony API PHP framework. The whole idea of this project is to allow the fast development of APIs or PoC over data definitions.

## Basic Installation
1. Initialize your project using Composer:
```
$ composer init
```
3. Add the fony dependency:
```
$ composer require geekcow/fony
```
3. Add the installation script into the `composer.json` file:
```json
  "scripts": {
     "setup-fony": "Geekcow\\Fony\\Installer\\Setup::init"
  }
```
4. Proceed to the installation script:
```
$ composer run-script setup-fony
```

## Notes:
June 2020: There is still a lot of work to do.
* Unit tests are not done
* Tooling is still in progress
* I want to add a sample of a complete implementation in the Installer.
* Wiki only has titles.

## Contributing

1. Fork it
2. Create your feature branch (`git checkout -b my-new-feature`)
3. Commit your changes (`git commit -am 'Add some feature'`)
4. Push to the branch (`git push origin my-new-feature`)
5. Create new Pull Request
