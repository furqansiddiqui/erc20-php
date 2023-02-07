<?php
/*
 * This file is a part of "furqansiddiqui/erc20-php" package.
 * https://github.com/furqansiddiqui/erc20-php
 *
 * Copyright (c) Furqan A. Siddiqui <hello@furqansiddiqui.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code or visit following link:
 * https://github.com/furqansiddiqui/erc20-php/blob/master/LICENSE
 */

declare(strict_types=1);

namespace FurqanSiddiqui\Ethereum\ERC20;

use FurqanSiddiqui\Ethereum\Buffers\EthereumAddress;
use FurqanSiddiqui\Ethereum\Contracts\DeployedContract;
use FurqanSiddiqui\Ethereum\ERC20\Exception\ERC20TokenException;

/**
 * Class ERC20_Token
 * @package ERC20
 */
class ERC20_Token extends DeployedContract
{
    /** @var string|null */
    private ?string $_name = null;
    /** @var string|null */
    private ?string $_symbol = null;
    /** @var int|null */
    private ?int $_decimals = null;
    /** @var string|null */
    private ?string $_totalSupply = null;

    /**
     * @return string
     * @throws \FurqanSiddiqui\Ethereum\ERC20\Exception\ERC20TokenException
     * @throws \FurqanSiddiqui\Ethereum\Exception\EthereumException
     */
    public function name(): string
    {
        return $this->constantCall("name", "_name", fn(string $name): string => $this->cleanOutputASCII($name));
    }

    /**
     * @return string
     * @throws \FurqanSiddiqui\Ethereum\ERC20\Exception\ERC20TokenException
     * @throws \FurqanSiddiqui\Ethereum\Exception\EthereumException
     */
    public function symbol(): string
    {
        return $this->constantCall("symbol", "_symbol", fn(string $symbol): string => $this->cleanOutputASCII($symbol));
    }

    /**
     * @return int
     * @throws \FurqanSiddiqui\Ethereum\ERC20\Exception\ERC20TokenException
     * @throws \FurqanSiddiqui\Ethereum\Exception\EthereumException
     */
    public function decimals(): int
    {
        return $this->constantCall("decimals", "_decimals", fn(string $dec): int => intval($dec));
    }

    /**
     * @return string
     * @throws \FurqanSiddiqui\Ethereum\ERC20\Exception\ERC20TokenException
     * @throws \FurqanSiddiqui\Ethereum\Exception\EthereumException
     */
    public function totalSupply(): string
    {
        return $this->constantCall("totalSupply", "_totalSupply", null);
    }

    /**
     * @param \FurqanSiddiqui\Ethereum\Buffers\EthereumAddress $address
     * @return string
     * @throws \FurqanSiddiqui\Ethereum\ERC20\Exception\ERC20TokenException
     * @throws \FurqanSiddiqui\Ethereum\Exception\EthereumException
     */
    public function balanceOf(EthereumAddress $address): string
    {
        $balance = $this->call("balanceOf", [strval($address)])["balance"] ?? null;
        if (!is_string($balance)) {
            throw new ERC20TokenException('Failed to retrieve address token balance');
        }

        return $balance;
    }

    /**
     * @param \FurqanSiddiqui\Ethereum\Buffers\EthereumAddress $dest
     * @param int|string $amount
     * @return string
     * @throws \FurqanSiddiqui\Ethereum\Exception\Contract_ABIException
     */
    public function encodeTransferData(EthereumAddress $dest, int|string $amount): string
    {
        return $this->encodeCall("transfer", [strval($dest), strval($amount)]);
    }

    /**
     * @param string $value
     * @return string
     * @throws \FurqanSiddiqui\Ethereum\ERC20\Exception\ERC20TokenException
     * @throws \FurqanSiddiqui\Ethereum\Exception\EthereumException
     */
    public function fromScaledValue(string $value): string
    {
        return bcmul($value, bcpow("10", strval($this->decimals()), 0), 0);
    }

    /**
     * @param int|string $value
     * @return string
     * @throws \FurqanSiddiqui\Ethereum\ERC20\Exception\ERC20TokenException
     * @throws \FurqanSiddiqui\Ethereum\Exception\EthereumException
     */
    public function getScaledValue(int|string $value): string
    {
        return bcdiv(strval($value), bcpow("10", strval($this->decimals()), 0), $this->decimals());
    }

    /**
     * @param string $func
     * @param string $prop
     * @param callable|null $manipulator
     * @return mixed
     * @throws \FurqanSiddiqui\Ethereum\ERC20\Exception\ERC20TokenException
     * @throws \FurqanSiddiqui\Ethereum\Exception\EthereumException
     */
    private function constantCall(string $func, string $prop, ?callable $manipulator): mixed
    {
        if (isset($this->$prop)) {
            return $this->$prop;
        }

        $constant = $this->call($func);
        if (!array_key_exists(0, $constant)) {
            throw new ERC20TokenException('Failed to retrieve ERC20 token ' . $prop);
        }

        $constant = $constant[0];
        if ($manipulator) {
            $constant = $manipulator($constant);
        }

        $this->$prop = $constant;
        return $this->$prop;
    }

    /**
     * @return array
     * @throws \FurqanSiddiqui\Ethereum\ERC20\Exception\ERC20TokenException
     * @throws \FurqanSiddiqui\Ethereum\Exception\EthereumException
     */
    public function __debugInfo(): array
    {
        return $this->toArray();
    }

    /**
     * @return void
     */
    public function purgeCached(): void
    {
        $this->_name = null;
        $this->_symbol = null;
        $this->_decimals = null;
        $this->_totalSupply = null;
    }

    /**
     * @return array
     * @throws \FurqanSiddiqui\Ethereum\ERC20\Exception\ERC20TokenException
     * @throws \FurqanSiddiqui\Ethereum\Exception\EthereumException
     */
    public function toArray(): array
    {
        return [
            "deployedAt" => $this->deployedAt->toString(true),
            "name" => $this->name(),
            "symbol" => $this->symbol(),
            "decimals" => $this->decimals(),
            "totalSupply" => $this->totalSupply()
        ];
    }
}
