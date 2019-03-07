[![Latest Stable Version](https://poser.pugx.org/thecodingmachine/phpstan-safe-rule/v/stable)](https://packagist.org/packages/thecodingmachine/phpstan-safe-rule)
[![Total Downloads](https://poser.pugx.org/thecodingmachine/phpstan-safe-rule/downloads)](https://packagist.org/packages/thecodingmachine/phpstan-safe-rule)
[![Latest Unstable Version](https://poser.pugx.org/thecodingmachine/phpstan-safe-rule/v/unstable)](https://packagist.org/packages/thecodingmachine/phpstan-safe-rule)
[![License](https://poser.pugx.org/thecodingmachine/phpstan-safe-rule/license)](https://packagist.org/packages/thecodingmachine/phpstan-safe-rule)
[![Build Status](https://travis-ci.org/thecodingmachine/phpstan-safe-rule.svg?branch=master)](https://travis-ci.org/thecodingmachine/phpstan-safe-rule)
[![Coverage Status](https://coveralls.io/repos/thecodingmachine/phpstan-safe-rule/badge.svg?branch=master&service=github)](https://coveralls.io/github/thecodingmachine/phpstan-safe-rule?branch=master)


PHPStan rules for thecodingmachine/safe
=======================================

The [thecodingmachine/safe](https://github.com/thecodingmachine/safe) package provides a set of core PHP functions rewritten to throw exceptions instead of returning `false` when an error is encountered.

This PHPStan rule will help you detect unsafe function call and will propose you to use the `thecodingmachine/safe` variant instead.

Please read [thecodingmachine/safe documentation](https://github.com/thecodingmachine/safe) for details about installation and usage.
