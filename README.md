# rlp

[![PHP](https://github.com/web3p/rlp/actions/workflows/php.yml/badge.svg)](https://github.com/web3p/rlp/actions/workflows/php.yml)
[![codecov](https://codecov.io/gh/web3p/rlp/branch/master/graph/badge.svg)](https://codecov.io/gh/web3p/rlp)
[![Licensed under the MIT License](https://img.shields.io/badge/License-MIT-blue.svg)](https://github.com/web3p/rlp/blob/master/LICENSE)

Recursive Length Prefix Encoding in PHP.

# Install

Set minimum stability to dev
```
composer require web3p/rlp
```

# Usage

RLP encode:

```php
use Web3p\RLP\RLP;

$rlp = new RLP;
// c483646f67
$encoded = $rlp->encode(['dog']);

// 83646f67
$encoded = $rlp->encode('dog');
```

RLP decode:

```php
use Web3p\RLP\RLP;
use Web3p\RLP\Types\Str;

$rlp = new RLP;
$encoded = $rlp->encode(['dog']);

// accept hex string with or without 0x prefix
$decoded = $rlp->decode('0x' . $encoded);

// show 646f67
echo $decoded[0];

// show dog
echo hex2bin($decoded[0]);

// or you can
echo Str::decodeHex($decoded[0]);
```

# API

### Web3p\RLP\RLP

#### encode

Returns recursive length prefix encoding of given inputs.

`encode(mixed $inputs)`

Mixed inputs - string, integer, null, or (nested) array of them.

* Strings starting with `0x` are encoded as hex bytes, byte for byte (leading zero bytes are kept). Strip leading zeros yourself for integer quantities, e.g. `0x00cb9d` should be passed as `0xcb9d`.
* Other strings are encoded as their raw bytes, numeric strings included (`'1024'` is encoded as the text `1024`).
* Integers must be non-negative and not greater than `PHP_INT_MAX`; pass larger values as hex strings.
* `null` is encoded as an empty string, arrays are encoded as lists.

Throws `InvalidArgumentException` for invalid hex strings, negative or fractional numbers and unsupported types.

> Note: output is not 0x prefixed.

###### Example

* Encode array of string.

```php
use Web3p\RLP\RLP;

$rlp = new RLP;
$encoded = $rlp->encode(['web3p', 'ethereum', 'solidity']);
```

#### decode

Returns recursive length prefix decoding of given data: a hex string for a string item, or a (nested) array for a list.

`decode(string $input)`

String input - recursive length prefix encoded hex string, with or without `0x` prefix.

Throws `InvalidArgumentException` if the input is not an even-length hex string, and `RuntimeException` if it is not valid canonical RLP (truncated data, trailing data or non-canonical lengths).

> Note: output is not 0x prefixed.

###### Example

* Decode recursive length prefix encoded string.

```php
use Web3p\RLP\RLP;
use Web3p\RLP\Types\Str;

$rlp = new RLP;
$encoded = $rlp->encode(['web3p', 'ethereum', 'solidity']);
$decoded = $rlp->decode('0x' . $encoded);

// echo web3p
echo hex2bin($decoded[0]);

// echo ethereum
echo hex2bin($decoded[1]);

// echo solidity
echo hex2bin($decoded[2]);

// or you can
echo Str::decodeHex($decoded[0]);
echo Str::decodeHex($decoded[1]);
echo Str::decodeHex($decoded[2]);
```

# License
MIT
