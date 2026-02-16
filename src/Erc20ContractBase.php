<?php
/*
 * Part of the "furqansiddiqui/erc20-php" package.
 * @link https://github.com/furqansiddiqui/erc20-php
 */

declare(strict_types=1);

namespace FurqanSiddiqui\Ethereum\Erc20;

use FurqanSiddiqui\Ethereum\Evm\AbiParam;
use FurqanSiddiqui\Ethereum\Evm\ContractEvent;
use FurqanSiddiqui\Ethereum\Evm\ContractMethod;
use FurqanSiddiqui\Ethereum\Evm\ContractMethodType;
use FurqanSiddiqui\Ethereum\Evm\SmartContract;

/**
 * Represents an ERC20 smart contract implementation. This class extends the base functionality of the
 * SmartContract class and implements the standard ERC20 contract methods as defined in the ERC20 standard.
 */
class Erc20ContractBase extends SmartContract
{
    private(set) ContractMethod $balanceOf;
    private(set) ContractMethod $transfer;
    private(set) ContractMethod $transferFrom;
    private(set) ContractMethod $approve;
    private(set) ContractMethod $allowance;

    /**
     * Erc20ContractBase constructor.
     */
    public function __construct()
    {
        parent::__construct();

        // Constants
        $this->declareConstantsFn();

        // Functions
        // balanceOf(address)
        $this->balanceOf = new ContractMethod(ContractMethodType::Function, "balanceOf", true, false);
        $this->balanceOf->appendInput(new AbiParam("address", null));
        $this->balanceOf->appendOutput(new AbiParam("uint256", null));
        $this->append($this->balanceOf);

        // Transfer Functions
        $this->declareTransferFns();
        $this->declareApproveFns();

        // Events
        $this->declareEvents();
    }

    /**
     * @return void
     */
    protected function declareConstantsFn(): void
    {
        $this->append(new ContractMethod(ContractMethodType::Function, "name", true, false)
            ->appendOutput(new AbiParam("string", null)));
        $this->append(new ContractMethod(ContractMethodType::Function, "symbol", true, false)
            ->appendOutput(new AbiParam("string", null)));
        $this->append(new ContractMethod(ContractMethodType::Function, "decimals", true, false)
            ->appendOutput(new AbiParam("uint8", null)));
        $this->append(new ContractMethod(ContractMethodType::Function, "totalSupply", true, false)
            ->appendOutput(new AbiParam("uint256", null)));
    }

    /**
     * @return void
     */
    protected function declareTransferFns(): void
    {
        // transfer(address,uint256)
        $this->transfer = new ContractMethod(ContractMethodType::Function, "transfer", false, false);
        $this->transfer->appendInput(new AbiParam("address", null));
        $this->transfer->appendInput(new AbiParam("uint256", null));
        $this->transfer->appendOutput(new AbiParam("bool", null));
        $this->append($this->transfer);

        // transferFrom(address,address,uint256)
        $this->transferFrom = new ContractMethod(ContractMethodType::Function, "transferFrom", false, false);
        $this->transferFrom->appendInput(new AbiParam("address", null));
        $this->transferFrom->appendInput(new AbiParam("address", null));
        $this->transferFrom->appendInput(new AbiParam("uint256", null));
        $this->transferFrom->appendOutput(new AbiParam("bool", null));
        $this->append($this->transferFrom);
    }

    /**
     * @return void
     */
    protected function declareApproveFns(): void
    {
        $this->approve = new ContractMethod(ContractMethodType::Function, "approve", false, false);
        $this->approve->appendInput(new AbiParam("address", null));
        $this->approve->appendInput(new AbiParam("uint256", null));
        $this->approve->appendOutput(new AbiParam("bool", null));
        $this->append($this->approve);

        $this->allowance = new ContractMethod(ContractMethodType::Function, "allowance", true, false);
        $this->allowance->appendInput(new AbiParam("address", null));
        $this->allowance->appendInput(new AbiParam("address", null));
        $this->allowance->appendOutput(new AbiParam("uint256", null));
        $this->append($this->allowance);
    }

    /**
     * @return void
     */
    protected function declareEvents(): void
    {
        $this->append(new ContractEvent("Transfer", false)
            ->appendInput(new AbiParam("address", "_from", true))
            ->appendInput(new AbiParam("address", "_to", true))
            ->appendInput(new AbiParam("uint256", "_value", false)));

        $this->append(new ContractEvent("Approval", false)
            ->appendInput(new AbiParam("address", "_owner", true))
            ->appendInput(new AbiParam("address", "_spender", true))
            ->appendInput(new AbiParam("uint256", "_value", false)));
    }
}