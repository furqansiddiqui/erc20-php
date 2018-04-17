# ERC20 Tokens

Interact with any ERC20 standard Ethereum token

This package is ultimate response to historic issue of no native API being available to PHP developers to interact with 
ERC20 tokens (i.e. web3js contracts API).

This package communicates directly with `Geth` using `RPC` ([furqansiddiqui/ethereum-rpc](https://github.com/furqansiddiqui/ethereum-rpc/)) and performs all `ABI` encoding and decoding in background, 
resulting in pure simple and easy to use API for developers to perform all ERC20 standard operations.


## Installation

`composer require furqansiddiqui/erc20-php`

### Prerequisites

* **PHP** >= 7.1+
* **Ethereum RPC client** ([furqansiddiqui/ethereum-rpc](https://github.com/furqansiddiqui/ethereum-rpc/)) > 1.0

## ABI

A standard ERC20 ABI file is included in package residing in "data" directory.

Path to a custom ABI may be specified when constructing ERC20 token object.

`````php

`````
