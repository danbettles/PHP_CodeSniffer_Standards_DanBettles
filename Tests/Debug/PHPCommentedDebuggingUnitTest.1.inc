<?php
/**
 * Tests `DanBettles_Debug_PHPCommentedDebugFunctions`, which adds warnings when it finds debugging functions 
 * (`print_r()` and `var_dump()`), output functions (`print` and `echo`), or script termination functions (`exit` and 
 * `die`) in comments.  In all comments except doc comments.
 */

//print_r($something);
//print_r('something');
//print_r("something");
//print_r(123);
//print_r(array());
//var_dump($something);
//var_dump('something');
//var_dump("something");
//var_dump(123);
//var_dump(array());
//print 'something';
//print "something";
//print $something;
//print('something');
//print("something");
//print($something);
//exit;
//die;
//exit();
//die();

/*print_r('something');*/
/*print_r("something");*/
/*print_r(123);*/
/*print_r(array());*/
/*var_dump($something);*/
/*var_dump('something');*/
/*var_dump("something");*/
/*var_dump(123);*/
/*var_dump(array());*/
/*print 'something';*/
/*print "something";*/
/*print $something;*/
/*print('something');*/
/*print("something");*/
/*print($something);*/
/*exit;*/
/*die;*/
/*exit();*/
/*die();*/