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

// only accept 0x prefixed hex string
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

Mixed inputs - array of string, integer or numeric string.

> Note: output is not zero prefixed.

###### Example

* Encode array of string.

```php
use Web3p\RLP\RLP;

$rlp = new RLP;
$encoded = $rlp->encode(['web3p', 'ethereum', 'solidity']);
```

#### decode

Returns array recursive length prefix decoding of given data.

`decode(string $input)`

String input - recursive length prefix encoded string.

> Note: output is not zero prefixed.

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
