<?php
/*
 * Please refer to README.md for a usage example.
 * Please refer to the LICENSE file for license details.
 */
function getMsPublicKeyBlob($modulus, $exponent)
{
    $key_length = strlen($modulus);
    // Pre-populate our "byte array".
    $blob = array();
    for ($i = 0; $i < 20 + $key_length; $i++)
        $blob[$i] = 0x00;
    $blob[0] = 0x06; // Type - PUBLICKEYBLOB (0x06)
    $blob[1] = 0x02; // Version - Always CUR_BLOB_VERSION (0x02)
    // [2], [3]      // RESERVED - Always 0
    $blob[5] = 0x24; // ALGID - Always 00 24 00 00 (for CALG_RSA_SIGN)
    $blob[8] = 0x52; // Magic - RSA1 (ASCII in hex)
    $blob[9] = 0x53;
    $blob[10] = 0x41;
    $blob[11] = 0x31;

    $val = $key_length - 1 << 3;
    $blob[12] = $val & 0xff;
    $blob[13] = (($val >> 8) & 0xff);
    $blob[14] = (($val >> 16) & 0xff);
    $blob[15] = (($val >> 24) & 0xff);

    // Public Exponent
    $exponent = str_split($exponent);
    $pos = 16;
    $n = count($exponent);
    while ($n > 0)
        $blob[$pos++] = ord($exponent[--$n]);

    // Modulus
    $pos = 20;
    $part = str_split($modulus);
    $len = count($part);
    $part = array_reverse($part);
    for ($i = 0; $i < $len; $i++)
    {
        $blob[$pos] = ord($part[$i]);
        $pos++;
    }

    // Remove blank element from the end of the array.
    array_splice($blob, count($blob)-1, 1);

    $public_key_blob = call_user_func_array("pack", array_merge(array("C*"), $blob));
    return $public_key_blob;
}
