<?php

$csv = array_map('str_getcsv', file('hrvatske_regije.csv'));

echo'<pre>';print_r($csv);echo'</pre>';