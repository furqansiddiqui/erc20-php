<?php
/*
 * Part of the "furqansiddiqui/erc20-php" package.
 * @link https://github.com/furqansiddiqui/erc20-php
 */

declare(strict_types=1);

namespace FurqanSiddiqui\Ethereum\Erc20;

use FurqanSiddiqui\Ethereum\Evm\DeployedContract;
use FurqanSiddiqui\Ethereum\Keypair\EthereumAddress;
use FurqanSiddiqui\Ethereum\Rpc\AbstractRpcClient;

/**
 * Class Erc20Token represents an ERC-20 token contract deployed to the Ethereum blockchain.
 * This class provides methods to interact with and retrieve information from an ERC-20 token contract,
 * such as balances, allowances, and token metadata.
 * @property-read ERc20ContractBase $abi
 */
class Erc20Token extends DeployedContract
{
    protected ?string $name = null;
    protected ?string $symbol = null;
    protected ?int $decimals = null;
    protected null|int|string $totalSupply = null;

    public function __construct(
        EthereumAddress   $address,
        Erc20ContractBase $abi,
        AbstractRpcClient $rpcClient
    )
    {
        parent::__construct($address, $abi, $rpcClient);
    }

    /**
     * @throws \FurqanSiddiqui\Ethereum\Rpc\EthereumRpcException
     */
    public function balanceOf(
        EthereumAddress|string $address,
        string                 $scope = "latest"
    ): string|int
    {
        $value = $this->call($this->abi->balanceOf, [$address], $scope)[0] ?? null;
        if ($value === null) {
            throw new \UnexpectedValueException("balanceOf() returned empty result");
        }

        return is_int($value) ? $value : (string)$value;
    }

    /**
     * @throws \FurqanSiddiqui\Ethereum\Rpc\EthereumRpcException
     */
    public function allowance(
        EthereumAddress|string $owner,
        EthereumAddress|string $spender,
        string                 $scope = "latest"
    ): string|int
    {
        $value = $this->call($this->abi->allowance, [$owner, $spender], $scope)[0] ?? null;
        if ($value === null) {
            throw new \UnexpectedValueException("allowance() returned empty result");
        }

        return is_int($value) ? $value : (string)$value;
    }

    /**
     * @param EthereumAddress|string $to
     * @param string|int $value
     * @return string
     */
    public function encodeTransfer(EthereumAddress|string $to, string|int $value): string
    {
        return $this->abi->transfer->abiEncodeCall([$to, $value]);
    }

    /**
     * @param EthereumAddress|string $from
     * @param EthereumAddress|string $to
     * @param string|int $value
     * @return string
     */
    public function encodeTransferFrom(
        EthereumAddress|string $from,
        EthereumAddress|string $to,
        string|int             $value
    ): string
    {
        return $this->abi->transferFrom->abiEncodeCall([$from, $to, $value]);
    }

    /**
     * @param EthereumAddress|string $spender
     * @param string|int $value
     * @return string
     */
    public function encodeApprove(
        EthereumAddress|string $spender,
        string|int             $value
    ): string
    {
        return $this->abi->approve->abiEncodeCall([$spender, $value]);
    }

    /**
     * @return string
     * @throws \FurqanSiddiqui\Ethereum\Rpc\EthereumRpcException
     */
    public function name(): string
    {
        if ($this->name !== null) {
            return $this->name;
        }

        return $this->name = $this->getConstant("name()");
    }

    /**
     * @return int
     * @throws \FurqanSiddiqui\Ethereum\Rpc\EthereumRpcException
     */
    public function decimals(): int
    {
        if ($this->decimals !== null) {
            return $this->decimals;
        }

        return $this->decimals = $this->getConstant("decimals()");
    }

    /**
     * @return string
     * @throws \FurqanSiddiqui\Ethereum\Rpc\EthereumRpcException
     */
    public function symbol(): string
    {
        if ($this->symbol !== null) {
            return $this->symbol;
        }

        return $this->symbol = $this->getConstant("symbol()");
    }

    /**
     * @return int|string
     * @throws \FurqanSiddiqui\Ethereum\Rpc\EthereumRpcException
     */
    public function totalSupply(): int|string
    {
        if ($this->totalSupply !== null) {
            return $this->totalSupply;
        }

        return $this->totalSupply = $this->getConstant("totalSupply()");
    }

    /**
     * @throws \FurqanSiddiqui\Ethereum\Rpc\EthereumRpcException
     */
    protected function getConstant(string $signature): mixed
    {
        $result = $this->call($this->methodFromSignature($signature));
        if (!array_key_exists(0, $result)) {
            throw new \UnexpectedValueException("Failed to retrieve ERC20 constant: " . $signature);
        }

        return $result[0];
    }

    /**
     * @param int|string $value
     * @return string
     * @throws \FurqanSiddiqui\Ethereum\Rpc\EthereumRpcException
     */
    public function getScaledValue(int|string $value): string
    {
        if ($this->decimals === null) {
            $this->decimals();
        }

        if ($this->decimals < 0) {
            throw new \UnexpectedValueException("Invalid decimals value");
        }

        $scale = bcpow("10", (string)$this->decimals, 0);
        return bcdiv((string)$value, $scale, $this->decimals);
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            "deployedAt" => (string)$this->address,
            "name" => $this->name,
            "symbol" => $this->symbol,
            "decimals" => $this->decimals,
            "totalSupply" => $this->totalSupply
        ];
    }

    /**
     * @return $this
     * @throws \FurqanSiddiqui\Ethereum\Rpc\EthereumRpcException
     */
    public function loadConstants(): static
    {
        $this->name();
        $this->symbol();
        $this->decimals();
        $this->totalSupply();
        return $this;
    }

    /**
     * @return void
     */
    public function flush(): void
    {
        $this->name = $this->symbol = $this->decimals = $this->totalSupply = null;
    }
}