<?php
/*
 * Part of the "furqansiddiqui/erc20-php" package.
 * @link https://github.com/furqansiddiqui/erc20-php
 */

declare(strict_types=1);

namespace FurqanSiddiqui\Ethereum\Erc20\Tests;

use Charcoal\Http\Client\ClientConfig;
use Charcoal\Http\Client\Enums\TlsVerify;
use Charcoal\Http\Client\Security\TlsContext;
use FurqanSiddiqui\Ethereum\Erc20\Erc20;
use FurqanSiddiqui\Ethereum\Erc20\Erc20ContractBase;
use FurqanSiddiqui\Ethereum\Keypair\EthereumAddress;
use FurqanSiddiqui\Ethereum\Rpc\GethRpc;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Class TokenRpcTest
 * @package FurqanSiddiqui\Ethereum\Erc20\Tests
 */
class TokenRpcTest extends TestCase
{
    private Erc20 $erc20;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $rpc = new GethRpc("https://1rpc.io/eth", null, new ClientConfig(
            tlsContext: new TlsContext(TlsVerify::Disable)
        ));

        $this->erc20 = new Erc20($rpc, new Erc20ContractBase());
    }

    /**
     * @throws \FurqanSiddiqui\Ethereum\Rpc\EthereumRpcException
     */
    #[DataProvider("provideTokenData")]
    public function testTokenMetadata(string $symbol, string $address, int $expectedDecimals): void
    {
        $token = $this->erc20->deployedAt(new EthereumAddress($address));
        $this->assertEquals($symbol, $token->symbol(),
            "Symbol mismatch for $symbol");
        $this->assertEquals($expectedDecimals, $token->decimals(),
            "Decimals mismatch for $symbol");
        $this->assertNotEmpty($token->name(),
            "Name should not be empty for $symbol");
        $totalSupply = $token->totalSupply();
        $this->assertTrue(is_int($totalSupply) || (is_string($totalSupply) && is_numeric($totalSupply)),
            "Total supply should be numeric for $symbol");
    }

    /**
     * @return array[]
     */
    public static function provideTokenData(): array
    {
        return [
            "USDC" => ["USDC", "0xA0b86991c6218b36c1d19D4a2e9Eb0cE3606eB48", 6],
            "DAI" => ["DAI", "0x6B175474E89094C44Da98b954EedeAC495271d0F", 18],
            "WETH" => ["WETH", "0xC02aaA39b223FE8D0A0e5C4F27eAD9083C756Cc2", 18],
            "LINK" => ["LINK", "0x514910771AF9Ca656af840dff83E8264EcF986CA", 18],
            "UNI" => ["UNI", "0x1f9840a85d5aF5bf1D1762F925BDADdC4201F984", 18],
        ];
    }
}
