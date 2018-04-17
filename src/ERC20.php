<?php
/**
 * This file is a part of "furqansiddiqui/erc20-php" package.
 * https://github.com/furqansiddiqui/erc20-php
 *
 * Copyright (c) 2018 Furqan A. Siddiqui <hello@furqansiddiqui.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code or visit following link:
 * https://github.com/furqansiddiqui/erc20-php/blob/master/LICENSE
 */

declare(strict_types=1);

namespace ERC20;

use EthereumRPC\EthereumRPC;

/**
 * Class ERC20
 * @package ERC20
 */
class ERC20
{
    /** @var EthereumRPC */
    private $client;
    /** @var string */
    private $abiPath;

    /**
     * ERC20 constructor.
     * @param EthereumRPC $ethereumRPC
     */
    public function __construct(EthereumRPC $ethereumRPC)
    {
        $this->client = $ethereumRPC;
        $this->reset();
    }

    /**
     * @return ERC20
     */
    public function reset(): self
    {
        $this->abiPath = sprintf('%1$s%2$sdata%2$serc20.abi', dirname(__FILE__, 2), DIRECTORY_SEPARATOR);
        return $this;
    }

    /**
     * @param string $path
     * @return ERC20
     */
    public function abiPath(?string $path = null): self
    {
        $this->abiPath = $path;
        return $this;
    }

    /**
     * @param string $contractAddress
     * @return ERC20_Token
     * @throws \EthereumRPC\Exception\ContractsException
     */
    public function token(string $contractAddress): ERC20_Token
    {
        $contract = $this->client->contract()->load($this->abiPath);
        return new ERC20_Token($this->client, $contract->abi(), $contractAddress);
    }
}