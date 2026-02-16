# ERC-20 Token Library for PHP

[![Tests](https://github.com/furqansiddiqui/erc20-php/actions/workflows/tests.yml/badge.svg)](https://github.com/furqansiddiqui/erc20-php/actions/workflows/tests.yml)
[![MIT License](https://img.shields.io/badge/license-MIT-green.svg)](LICENSE)

A PHP library for interacting with ERC-20 smart contracts on the Ethereum blockchain. This library uses the [ethereum-php](https://github.com/furqansiddiqui/ethereum-php) package for RPC communication and EVM interactions.

## Features

- Fetch token metadata (name, symbol, decimals, total supply).
- Retrieve account balances and allowances.
- Encode data for standard ERC-20 transactions (`transfer`, `transferFrom`, `approve`).
- Support for custom/extended ERC-20 ABIs.
- Fully compatible with PHP 8.5+.

## Requirements

- **PHP:** ^8.5
- **Extensions:** `openssl`, `gmp`, `bcmath`

## Installation

Install the package via Composer:

```bash
composer require furqansiddiqui/erc20-php
```

## Usage

### Initialization

To start, you need an Ethereum RPC client (e.g., Geth or an Infura/Alchemy endpoint) and the `Erc20` handler.

```php
use FurqanSiddiqui\Ethereum\Rpc\GethRpc;
use FurqanSiddiqui\Ethereum\Erc20\Erc20;
use FurqanSiddiqui\Ethereum\Keypair\EthereumAddress;

$rpc = new GethRpc("https://mainnet.infura.io/v3/YOUR_PROJECT_ID");
$erc20 = new Erc20($rpc);

// USDC Token Address
$usdcAddress = new EthereumAddress("0xA0b86991c6218b36c1d19D4a2e9Eb0cE3606eB48");
$token = $erc20->deployedAt($usdcAddress);
```

### Token Metadata

```php
echo "Name: " . $token->name() . PHP_EOL;
echo "Symbol: " . $token->symbol() . PHP_EOL;
echo "Decimals: " . $token->decimals() . PHP_EOL;
echo "Total Supply: " . $token->totalSupply() . PHP_EOL;
```

### Balances and Allowances

```php
$userAddress = new EthereumAddress("0x...");

// Get raw balance (uint256)
$balance = $token->balanceOf($userAddress);
echo "Raw Balance: " . $balance . PHP_EOL;

// Get allowance
$spender = new EthereumAddress("0x...");
$allowance = $token->allowance($userAddress, $spender);
```

### Encoding Transactions

This library helps you encode the `data` field for Ethereum transactions.

```php
// Encode a transfer of 100 USDC (USDC has 6 decimals)
$toAddress = "0x...";
$amount = 100 * (10 ** 6);
$data = $token->encodeTransfer($toAddress, $amount);

// You can now use this $data in an 'eth_sendTransaction' or 'eth_sendRawTransaction' call
```

## Extending for Custom ABIs

If you are dealing with a contract that follows ERC-20 but has additional methods, you can extend `Erc20ContractBase`.

```php
use FurqanSiddiqui\Ethereum\Erc20\Erc20ContractBase;
use FurqanSiddiqui\Ethereum\Evm\ContractMethod;
use FurqanSiddiqui\Ethereum\Evm\ContractMethodType;
use FurqanSiddiqui\Ethereum\Evm\AbiParam;

class MyCustomTokenAbi extends Erc20ContractBase
{
    public function __construct()
    {
        parent::__construct();
        
        // Add a custom method: burn(uint256)
        $burn = new ContractMethod(ContractMethodType::Function, "burn", false, false);
        $burn->appendInput(new AbiParam("uint256", null));
        $this->append($burn);
    }
}

// Usage
$erc20 = new Erc20($rpc, new MyCustomTokenAbi());
```

## Testing

The library includes a PHPUnit test suite. To run the tests, ensure you have dependencies installed:

```bash
composer install
./vendor/bin/phpunit
```

## License

This package is open-source software licensed under the [MIT license](LICENSE).
