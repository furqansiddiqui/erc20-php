<?php
/*
 * Part of the "furqansiddiqui/erc20-php" package.
 * @link https://github.com/furqansiddiqui/erc20-php
 */

declare(strict_types=1);

namespace FurqanSiddiqui\Ethereum\Erc20;

use FurqanSiddiqui\Ethereum\Keypair\EthereumAddress;
use FurqanSiddiqui\Ethereum\Rpc\AbstractRpcClient;

/**
 * Represents an ERC-20 token interaction handler.
 * This class provides functionality to interact with ERC-20 tokens
 * using an Ethereum RPC client and a specific contract's ABI.
 */
readonly class Erc20
{
    public function __construct(
        protected AbstractRpcClient      $rpcClient,
        protected(set) Erc20ContractBase $abi = new Erc20ContractBase(),
    )
    {
    }

    /**
     * @param EthereumAddress $address
     * @return Erc20Token
     */
    public function deployedAt(EthereumAddress $address): Erc20Token
    {
        return new Erc20Token($address, $this->abi, $this->rpcClient);
    }
}
