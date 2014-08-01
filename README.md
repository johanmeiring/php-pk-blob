php-pk-blob
===========

php-pk-blob provides a PHP function to convert a public key to a Microsoft CAPI Public Key BLOB, based on its exponent and modulus.  The result of `getMsPublicKeyBlob()` should be exactly the same as that of `RSACryptoServiceProvider.exportCspBlob()`, for the same key.  The PHP code is basically just a line-by-line copy of https://github.com/mono/mono/blob/master/mcs/class/Mono.Security/Mono.Security.Cryptography/CryptoConvert.cs#L535.

## Usage
I recommend using this along with the `Crypt/RSA.php` class included with https://github.com/phpseclib/phpseclib.  A basic usage example is as follows:

```php
<?php
...
$rsa = new Crypt_RSA();
$rsa->setPassword('passphrase');
$rsa->loadKey(file_get_contents('cert.pem'));
$public_key_raw = $rsa->getPublicKey(CRYPT_RSA_PUBLIC_FORMAT_RAW);
$public_key = getMsPublicKeyBlob($public_key_raw['n']->toBytes(true), $public_key_raw['e']->toBytes(true));
$public_key_encoded = base64_encode($public_key);
```
