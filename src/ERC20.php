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

use FurqanSiddiqui\Ethereum\Accounts\Account;
use FurqanSiddiqui\Ethereum\Contracts\ABI_Factory;
use FurqanSiddiqui\Ethereum\Contracts\Contract_ABI;
use FurqanSiddiqui\Ethereum\ERC20\Exception\ERC20TokenException;
use FurqanSiddiqui\Ethereum\Ethereum;
use FurqanSiddiqui\Ethereum\Exception\AccountsException;
use FurqanSiddiqui\Ethereum\RPC\AbstractRPCClient;

/**
 * Class ERC20
 * @package FurqanSiddiqui\Ethereum\ERC20
 */
class ERC20
{
    /** @var Ethereum */
    private Ethereum $eth;
    /** @var Contract_ABI */
    private Contract_ABI $erc20ABI;
    /** @var AbstractRPCClient|null */
    private ?AbstractRPCClient $rpcClient = null;

    /**
     * ERC20 constructor.
     * @param Ethereum $eth
     * @throws \FurqanSiddiqui\Ethereum\Exception\ContractsException
     */
    public function __construct(Ethereum $eth)
    {
        $this->eth = $eth;
        $abiFilePath = dirname(__FILE__, 2) . DIRECTORY_SEPARATOR . "data" . DIRECTORY_SEPARATOR . "erc20-abi.json";
        $this->erc20ABI = (new ABI_Factory())->fromFile($abiFilePath);
    }

    /**
     * @param AbstractRPCClient $rpcClient
     * @return $this
     */
    public function useRPCClient(AbstractRPCClient $rpcClient): self
    {
        $this->rpcClient = $rpcClient;
        return $this;
    }

    /**
     * @param $contractAddress
     * @return ERC20_Token
     * @throws ERC20TokenException
     */
    public function token($contractAddress): ERC20_Token
    {
        if (is_string($contractAddress)) {
            try {
                $contractAddress = $this->eth->getAccount($contractAddress);
            } catch (AccountsException $e) {
            }
        }

        if (!$contractAddress instanceof Account) {
            throw new ERC20TokenException('First argument must be valid contract address');
        }

        return new ERC20_Token($this->erc20ABI->abi(), $contractAddress, $this->rpcClient);
    }
}
