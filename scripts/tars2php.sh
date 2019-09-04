#!/bin/bash

cd ../tars/

php ../src/vendor/phptars/tars2php/src/tars2php.php ./tars.proto.php
php ../src/vendor/phptars/tars2php/src/tars2php.php ./Shop.php
php ../src/vendor/phptars/tars2php/src/tars2php.php ./OrderSystem.php
php ../src/vendor/phptars/tars2php/src/tars2php.php ./IntegralSystem.php
php ../src/vendor/phptars/tars2php/src/tars2php.php ./PAY.PayService.php