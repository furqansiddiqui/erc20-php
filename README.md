# ERC20 Tokens

Interact with any ERC20 standard Ethereum token

This package is ultimate response to historic issue of no native API being available to PHP developers to interact with 
ERC20 tokens (i.e. web3js contracts API).

This package relies on [furqansiddiqui/ethereum-php](https://github.com/furqansiddiqui/ethereum-php/) package to perform all `ABI` encoding and decoding, 
as well as communication with Ethereum node using RPC/API, resulting in pure simple and easy to use API for developers to perform all ERC20 standard operations.

Ethereum Node | Status
--- | ---
Geth / Ethereum-Go RPC | :heavy_check_mark:
Infuri.IO | :heavy_check_mark:

## Demo

* Testing interaction with `Thether USD` / `USDT` ERC20 smart contract:

`````php
$eth = new \FurqanSiddiqui\Ethereum\Ethereum();
$infura = new \FurqanSiddiqui\Ethereum\RPC\InfuraAPI($eth, "PROJECT-ID", "PROJECT-SECRET");
$infura->ignoreSSL(); // In case Infura.IO SSL errors

$erc20 = new \FurqanSiddiqui\Ethereum\ERC20\ERC20($eth);
$erc20->useRPCClient($infura);

$usdt = $erc20->token("0xdac17f958d2ee523a2206206994597c13d831ec7");
var_dump($usdt->name());
var_dump($usdt->symbol());
var_dump($usdt->decimals());
var_dump($usdt->totalSupply());
var_dump($usdt->balanceOf($eth->getAccount("ETHEREUM-ADDRESS")));
`````

Result:

```
string(9) "TetherUSD"
string(4) "USDT"
int(6)
string(18) "10034907979.686358"
string(12) "53150.417979"
```

### Prerequisites

* **PHP** >= 7.4+
* **Ethereum PHP lib** ([furqansiddiqui/ethereum-php](https://github.com/furqansiddiqui/ethereum-php/)) > 0.1.1


## Installation

`composer require furqansiddiqui/erc20-php`
