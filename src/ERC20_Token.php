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

use Comely\DataTypes\BcMath\BcMath;
use FurqanSiddiqui\Ethereum\Accounts\Account;
use FurqanSiddiqui\Ethereum\Contracts\Contract;
use FurqanSiddiqui\Ethereum\ERC20\Exception\ERC20TokenException;

/**
 * Class ERC20_Token
 * @package ERC20
 */
class ERC20_Token extends Contract
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
     * @return array
     * @throws ERC20TokenException
     * @throws \FurqanSiddiqui\Ethereum\Exception\ContractsException
     * @throws \FurqanSiddiqui\Ethereum\Exception\RPCException
     */
    public function __debugInfo(): array
    {
        return $this->array();
    }

    /**
     * Clears cached values
     */
    public function clearCached(): void
    {
        $this->_name = null;
        $this->_symbol = null;
        $this->_decimals = null;
        $this->_totalSupply = null;
    }

    /**
     * @return array
     * @throws ERC20TokenException
     * @throws \FurqanSiddiqui\Ethereum\Exception\ContractsException
     * @throws \FurqanSiddiqui\Ethereum\Exception\RPCException
     */
    public function array(): array
    {
        return [
            "name" => $this->name(),
            "symbol" => $this->symbol(),
            "decimals" => $this->decimals(),
            "totalSupply" => $this->totalSupply(true)
        ];
    }

    /**
     * @return string
     * @throws ERC20TokenException
     * @throws \FurqanSiddiqui\Ethereum\Exception\ContractsException
     * @throws \FurqanSiddiqui\Ethereum\Exception\RPCException
     */
    public function name(): string
    {
        if ($this->_name) {
            return $this->_name;
        }

        $result = $this->call("name");
        $name = $result[0] ?? null;
        if (!is_string($name)) {
            throw new ERC20TokenException('Failed to retrieve ERC20 token name');
        }

        $this->_name = $this->cleanStr($name);
        return $this->_name;
    }

    /**
     * @return string
     * @throws ERC20TokenException
     * @throws \FurqanSiddiqui\Ethereum\Exception\ContractsException
     * @throws \FurqanSiddiqui\Ethereum\Exception\RPCException
     */
    public function symbol(): string
    {
        if ($this->_symbol) {
            return $this->_symbol;
        }

        $result = $this->call("symbol");
        $code = $result[0] ?? null;
        if (!is_string($code)) {
            throw new ERC20TokenException('Failed to retrieve ERC20 token symbol');
        }

        $this->_symbol = $this->cleanStr($code);
        return $this->_symbol;
    }

    /**
     * @return int
     * @throws ERC20TokenException
     * @throws \FurqanSiddiqui\Ethereum\Exception\ContractsException
     * @throws \FurqanSiddiqui\Ethereum\Exception\RPCException
     */
    public function decimals(): int
    {
        if ($this->_decimals) {
            return $this->_decimals;
        }

        $result = $this->call("decimals");
        $scale = intval($result[0] ?? null);
        if (is_null($scale)) {
            throw new ERC20TokenException('Failed to retrieve ERC20 token decimals/scale value');
        }

        $this->_decimals = $scale;
        return $this->_decimals;
    }

    /**
     * @param bool $scaled
     * @return string
     * @throws ERC20TokenException
     * @throws \FurqanSiddiqui\Ethereum\Exception\ContractsException
     * @throws \FurqanSiddiqui\Ethereum\Exception\RPCException
     */
    public function totalSupply(bool $scaled = true): string
    {
        if (!$this->_totalSupply) {
            $result = $this->call("totalSupply");
            $totalSupply = $result[0] ?? null;
            if (!is_string($totalSupply) || !preg_match('/^[0-9]+$/', $totalSupply)) {
                throw new ERC20TokenException('Failed to retrieve ERC20 token totalSupply');
            }

            $this->_totalSupply = $totalSupply;
        }

        return $scaled ? $this->decimalValue($this->_totalSupply, $this->decimals()) : $this->_totalSupply;
    }

    /**
     * @param Account $account
     * @param bool $scaled
     * @return string
     * @throws ERC20TokenException
     * @throws \FurqanSiddiqui\Ethereum\Exception\ContractsException
     * @throws \FurqanSiddiqui\Ethereum\Exception\RPCException
     */
    public function balanceOf(Account $account, bool $scaled = true): string
    {
        $addr = $account->getAddress();
        $result = $this->call("balanceOf", [$addr]);
        $balance = $result["balance"] ?? null;
        if (!is_string($balance) || !preg_match('/^[0-9]+$/', $balance)) {
            throw new ERC20TokenException(
                sprintf('Failed to retrieve ERC20 token balance of address "%s"', $addr)
            );
        }

        return $scaled ? $this->decimalValue($balance, $this->decimals()) : $balance;
    }

    /**
     * @param Account $payee
     * @param string $amount
     * @return string
     * @throws ERC20TokenException
     * @throws \FurqanSiddiqui\Ethereum\Exception\ContractsException
     * @throws \FurqanSiddiqui\Ethereum\Exception\RPCException
     */
    public function encodedTransferData(Account $payee, string $amount): string
    {
        $amount = BcMath::isNumeric($amount);
        if (!$amount || $amount->isNegative()) {
            throw new ERC20TokenException('Invalid transaction amount');
        }

        $scale = $this->decimals();
        $amount = $amount->mul(pow(10, $scale), 0);
        return $this->abi()->encodeCall("transfer", [$payee->getAddress(), $amount->value()]);
    }
}
