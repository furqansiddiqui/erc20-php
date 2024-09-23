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
use FurqanSiddiqui\Ethereum\Contracts\Contract;
use FurqanSiddiqui\Ethereum\RPC\Abstract_RPC_Client;

/**
 * Class ERC20
 * @package FurqanSiddiqui\Ethereum\ERC20
 */
readonly class ERC20
{
    /**
     * @param \FurqanSiddiqui\Ethereum\RPC\Abstract_RPC_Client $rpcClient
     * @param \FurqanSiddiqui\Ethereum\Contracts\Contract $abi
     */
    public function __construct(
        public Abstract_RPC_Client $rpcClient,
        public Contract            $abi = new BaseERC20Contract(),
    )
    {
    }

    /**
     * @param \FurqanSiddiqui\Ethereum\Buffers\EthereumAddress $address
     * @return \FurqanSiddiqui\Ethereum\ERC20\ERC20_Token
     */
    public function deployedAt(EthereumAddress $address): ERC20_Token
    {
        return new ERC20_Token($this->abi, $address, $this->rpcClient);
    }
}
