# PHP_CodeSniffer Standard

> [!IMPORTANT]
> This version of my PHP_CodeSniffer Standard is for PHP_CodeSniffer 4 and PHP 8.3+.  Previous major versions target older incarnations of PHP_CodeSniffer and PHP.

> [!TIP]
> You may like to read about [how to unit-test a custom PHPCS Standard without having to 'install' it](#the-unit-tests)

## The Sniffs

### Errors

- PHP debugging functions (`print_r` and `var_dump`) are forbidden because I never use them for anything other than debugging.

### Warnings

- PHP output functions (`print` and `echo`) are considered suspect because I rarely&mdash;if ever&mdash;use them for anything other than debugging in object-oriented code, and since I tend to use short-echo tags (`<?= ?>`) in output templates, I don't expect to see them elsewhere either.
- PHP script termination functions (`exit` and `die`) are considered suspect because they needn't be used in well-structured code; I use them only when debugging.  I consider it bad-form to use these functions in a framework because they will likely prevent an application shutting-down cleanly, which, for one thing, can make it difficult to find out why output filters&mdash;or the like&mdash;are not being applied.
- ~~JavaScript's `alert` function (`window.alert()`) is considered suspect because it's probably used more for debugging than anything else.~~
- Comments appearing to contain debugging code will be the subject of warnings.  Debugging code should never be committed, even if it's commented.  At the very least, commented debugging code is garbage, unwanted noise, and there's no sense in making your code more difficult to follow by leaving it lying around.
- One way or another, `TODO` and `FIXME` tags signal potential problems on the road ahead.

## Installation

Install using Composer: `composer --dev require danbettles/codesniffer-standard`.

## Usage

You can reference the Standard on the command-line with `--standard=path/to/vendor/danbettles/codesniffer-standard/src/DanBettles`.

Alternatively, add the following Rule Element to your XML config file.

```xml
<rule ref="path/to/vendor/danbettles/codesniffer-standard/src/DanBettles">
</rule>
```

## The Unit Tests

I had some difficulty upgrading the unit-tests for PHP_CodeSniffer 3 and I still didn't like that you have to 'install' your custom Standard as part of the usual PHPCS approach.  After some investigation, I tried [Payton Swick's solution](https://payton.codes/2017/12/15/creating-sniffs-for-a-phpcs-standard/#writing-tests), which worked out well&mdash;thank you, Payton.

Payton Swick's solution enables you to test your custom Standard in isolation, within the project.  I developed his idea a little further, creating a sensibly opinionated test-case base-class that makes it straightforward to test your sniffs: straightforward if you follow usual PHPUnit conventions, and straightforward because it involves using PHPUnit in a more natural way.
