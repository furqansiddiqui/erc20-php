# ERC20 Tokens

Interact with any ERC20 standard Ethereum token

This package is ultimate response to historic issue of no native API being available to PHP developers to interact with 
ERC20 tokens (i.e. web3js contracts API).

This package communicates directly with `Geth` using `RPC` ([furqansiddiqui/ethereum-rpc](https://github.com/furqansiddiqui/ethereum-rpc/)) and performs all `ABI` encoding and decoding in background, 
resulting in pure simple and easy to use API for developers to perform all ERC20 standard operations.

## Demo

* A random ERC20 token was picked from a list, given contract address `0xd26114cd6EE289AccF82350c8d8487fedB8A0C07`

`````php
$geth = new EthereumRPC('127.0.0.1', 8545);
$erc20 = new \ERC20\ERC20($geth);
$token = $erc20->token('0xd26114cd6EE289AccF82350c8d8487fedB8A0C07');

var_dump($token->name());
var_dump($token->symbol());
var_dump($token->decimals());
`````

Result:

```
string(8) "OMGToken"
string(3) "OMG"
int(18)
```

## Installation

`composer require furqansiddiqui/erc20-php`

### Prerequisites

* **PHP** >= 7.1+
* **Ethereum RPC client** ([furqansiddiqui/ethereum-rpc](https://github.com/furqansiddiqui/ethereum-rpc/)) > 1.0

## ABI

A standard ERC20 ABI file is included in package residing in "data" directory.

Path to a custom ABI may be specified when constructing ERC20 token object.

`````php
$geth = new EthereumRPC('127.0.0.1', 8545);
$erc20 = new \ERC20\ERC20($geth);
$erc20->abiPath('/path/to/abi.json');
`````
