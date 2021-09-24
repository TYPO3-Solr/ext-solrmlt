#!/usr/bin/env bash

echo "PWD: $(pwd)"

export TYPO3_PATH_WEB="$(pwd)/.Build/Web"
export TYPO3_PATH_PACKAGES="$(pwd)/.Build/vendor/"


if [ $TRAVIS ]; then
    # Travis does not have composer's bin dir in $PATH
    export PATH="$PATH:$HOME/.composer/vendor/bin"
fi

# use from vendor dir
php-cs-fixer --version > /dev/null 2>&1
if [ $? -eq "0" ]; then
    echo "Check PSR-2 compliance"
    php-cs-fixer fix --diff --verbose --dry-run --rules='{"function_declaration": {"closure_function_spacing": "none"}}' Classes

    if [ $? -ne "0" ]; then
        echo "Some files are not PSR-2 compliant"
        echo "Please fix the files listed above"
        exit 1
    fi
fi


echo "Run unit tests"
UNIT_BOOTSTRAP=".Build/vendor/nimut/testing-framework/res/Configuration/UnitTestsBootstrap.php"
if ! .Build/bin/phpunit --colors -c Build/Test/UnitTests.xml --coverage-clover=coverage.unit.clover --bootstrap=$UNIT_BOOTSTRAP; then
  echo "Error during running the unit tests please check and fix them"
  exit 1
fi

#
# Map the travis and shell variale names to the expected
# casing of the TYPO3 core.
#

#echo "Run integration tests"
#if [ -n $TYPO3_DATABASE_NAME ]; then
#	export typo3DatabaseName=$TYPO3_DATABASE_NAME
#else
#	echo "No environment variable TYPO3_DATABASE_NAME set. Please set it to run the integration tests."
#	exit 1
#fi
#
#if [ -n $TYPO3_DATABASE_HOST ]; then
#	export typo3DatabaseHost=$TYPO3_DATABASE_HOST
#else
#	echo "No environment variable TYPO3_DATABASE_HOST set. Please set it to run the integration tests."
#	exit 1
#fi
#
#if [ -n $TYPO3_DATABASE_USERNAME ]; then
#	export typo3DatabaseUsername=$TYPO3_DATABASE_USERNAME
#else
#	echo "No environment variable TYPO3_DATABASE_USERNAME set. Please set it to run the integration tests."
#	exit 1
#fi
#
#if [ -n $TYPO3_DATABASE_PASSWORD ]; then
#	export typo3DatabasePassword=$TYPO3_DATABASE_PASSWORD
#else
#	echo "No environment variable TYPO3_DATABASE_PASSWORD set. Please set it to run the integration tests."
#	exit 1
#fi
#
#INTEGRATION_BOOTSTRAP=".Build/vendor/nimut/testing-framework/res/Configuration/FunctionalTestsBootstrap.php"
#.Build/bin/phpunit --colors -c Build/Test/IntegrationTests.xml --coverage-html=../../../solrmlt-coverage-integration/ --bootstrap=$INTEGRATION_BOOTSTRAP
