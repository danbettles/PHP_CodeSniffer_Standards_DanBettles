#PHP_CodeSniffer Standard#

##Errors##

* PHP debugging functions (`print_r` and `var_dump`) are forbidden because I never use them for anything other than debugging.

##Warnings##

* PHP output functions (`print` and `echo`) are considered suspect because I rarely - if ever - use them for anything other than debugging in object-oriented code, and, since I tend to use short-echo tags (`<?= ?>`) in output templates, I don't expect to see them elsewhere either.

* PHP script termination functions (`exit` and `die`) are considered suspect because - quite simply - they needn't be used in well-structured code; I use them only when debugging.  I consider it bad-form to use these functions in a framework because they will likely prevent an application shutting-down cleanly, which, for one thing, can make it difficult to find out why output filters - or the like - are not being applied.

* JavaScript's `alert` function (`window.alert()`) is considered suspect because it's probably used more for debugging than anything else.

* Comments appearing to contain debugging code will be the subject of warnings.  Debugging code should never be committed, even if it's commented.  At the very least, commented debugging code is garbage, unwanted noise, and there's no sense in making your code more difficult to follow by leaving it lying around.