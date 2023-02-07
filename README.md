# ERC20 Tokens

Interact with any ERC20 standard Ethereum token

This package is ultimate response to historic issue of no native API being available to PHP developers to interact with
ERC20 tokens (i.e. web3js contracts API).

This package relies on [furqansiddiqui/ethereum-php](https://github.com/furqansiddiqui/ethereum-php/) package to perform
all `ABI` encoding and decoding,
as well as communication with Ethereum node using RPC/API, resulting in pure simple and easy to use API for developers
to perform all ERC20 standard operations.

| Ethereum Node          | Status             |
|------------------------|--------------------|
| Geth / Ethereum-Go RPC | :heavy_check_mark: |
| Infura.IO              | :heavy_check_mark: |

## Demo

* Testing interaction with `Thether USD` / `USDT` ERC20 smart contract:

`````php
use FurqanSiddiqui\Ethereum\RPC\Infura;
use FurqanSiddiqui\Ethereum\ERC20\ERC20;

$infura = new Infura("PROJECT-ID", "PROJECT-SECRET");
$infura->ignoreSSL(); // In case Infura.IO SSL errors (or provide "caRootFile:" to constructor above)

$erc20 = new ERC20($infura);
$usdt = $erc20->deployedAt("0xdac17f958d2ee523a2206206994597c13d831ec7");
var_dump($usdt->name());
var_dump($usdt->symbol());
var_dump($usdt->decimals());
var_dump($usdt->totalSupply());
$balance = $usdt->balanceOf($eth->getAccount("ETHEREUM-ADDRESS"));
var_dump($balance);
var_dump($usdt->getScaledValue($balance));
`````

Result:

```
string(9) "TetherUSD"
string(4) "USDT"
int(6)
string(18) "32284517903064882"
string(11) "53150417979"
string(12) "53150.417979"
```

## Custom ABI

* Standard/base ERC20 ABI is included in package.
* To included extended/custom ERC20 ABI (depending on your requirments), use one of the following methods:
    * Extend [BaseERC20Contract](src/BaseERC20Contract.php) class to define custom ABI functions and events.
    * Decode your custom ABI JSON file and provide it to constructor:

```php
use \FurqanSiddiqui\Ethereum\Contracts\Contract;

$customABI  =   Contract::fromArray(json_decode(file_get_contents("YOUR-CUSTOM-ABI.json"), true), true);
$erc20 = new ERC20(abi: $customABI);
```

### Scaled Values

Use `getScaledValue` and `fromScaledValue` to convert amounts from/to `uint256`.

### Prerequisites

* **PHP** ^8.1
* **Ethereum PHP lib** ([furqansiddiqui/ethereum-php](https://github.com/furqansiddiqui/ethereum-php/)) > 0.2.0

## Installation

`composer require furqansiddiqui/erc20-php`

## Changelog

* **0.3.0**: This library alongside several others such
  as [ethereum-php](https://github.com/furqansiddiqui/ethereum-php/) have been modernised and requires PHP ^8.1.
