#!/bin/sh
php -S localhost:${1:-"8080"} .router.php
