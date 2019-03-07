<?php
use function Safe\fopen;

function foo() {
    $fp = fopen('foobar', 'r');
}
