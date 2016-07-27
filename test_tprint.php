<?php
/**
 * @file    test_tprint.php
 *
 * Example and test for TPrint class of libtprint.
 *
 * @author  pitch314
 * @version 1.0, 2015-09-13
 */

require_once("TPrint.php");

function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

$d1 = 40.488; $d2 = 112.908; $d3 = 3.23;
$i1 = 532; $i2 = 3;

$tp = new TPrint(TRUE, TRUE, 2, 4);

$tp->tprint_column_add("", ITPrint::TPALIGN_CENTER, ITPrint::TPALIGN_RIGHT);
$tp->tprint_column_add("Align left", ITPrint::TPALIGN_CENTER, ITPrint::TPALIGN_LEFT);
$tp->tprint_column_add("Align right", ITPrint::TPALIGN_CENTER, ITPrint::TPALIGN_RIGHT);
$tp->tprint_column_add(NULL, ITPrint::TPALIGN_CENTER, ITPrint::TPALIGN_LEFT);
$tp->tprint_column_add("Align center", ITPrint::TPALIGN_CENTER, ITPrint::TPALIGN_CENTER);

for ($i = 0; $i < 10; $i++) {
    $s = generateRandomString(rand(1,10));
    $tp->tprint_data_add(0, $s);
    $d1 *= 2;
    $tp->tprint_data_add(1, $d1);
    $d2 *= 3;
    $tp->tprint_data_add(2, $d2);
    $i1 *= 2;
    $tp->tprint_data_add(3, $i1);
    $i2 *= 3;
    $tp->tprint_data_add(4, $i2);
}
$tp->tprint_add_row(array($d1, $d2, $d3));
$tp->tprint_add_row(array($d1, $d2, $d3, $i1, $i2, 10));

//$tp->setSymbols("|", "=", "_", " ", ".");
$tp->tprint_print();
//print_r($tp);
echo $tp->sout;
print_r( $tp->getSymbols());
print_r( $tp->getWitdh());
print_r( $tp->getWitdh(TRUE));
?>