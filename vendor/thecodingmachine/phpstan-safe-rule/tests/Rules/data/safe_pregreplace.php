<?php
use function Safe\preg_replace;

$x = preg_replace('/foo/', 'bar', 'baz');
$y = stripos($x, 'foo');

$x = preg_replace(['/foo/'], ['bar'], ['baz']);
