<?php

namespace Luigel\LaravelPaymongo\Tests;

use Luigel\LaravelPaymongo\Facades\PaymongoFacade;
use Orchestra\Testbench\TestCase;
use Luigel\LaravelPaymongo\LaravelPaymongoServiceProvider;
use Luigel\LaravelPaymongo\Models\Card;
use Luigel\LaravelPaymongo\Models\Token;

class TokenTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [LaravelPaymongoServiceProvider::class];
    }
    
    /** @test */
    public function it_can_create_token()
    {
        $token = PaymongoFacade::token()
                    ->create([
                        'number' => '4242424242424242',
                        'exp_month' => 12,
                        'exp_year' => 25,
                        'cvc' => "123",
                    ]);

        $this->assertInstanceOf(Token::class, $token);
        $this->assertInstanceOf(Card::class, $token->card);
        $this->assertEquals($token->card->last4, '4242');
        $this->assertEquals($token->card->exp_month, 12);
    }
    
    /** @test */
    public function it_cannot_create_token_with_invalid_data()
    {
        $token = PaymongoFacade::token()
                    ->create([
                        'number' => '424242424242424222',
                        'exp_month' => 12,
                        'exp_year' => 25,
                        'cvc' => "123",
        ]);
        
        $this->assertEquals('Your card number is invalid.', $token);

    }

    /** @test */
    public function it_can_retrieve_token()
    {
        $createdToken = PaymongoFacade::token()
                    ->create([
                        'number' => '4242424242424242',
                        'exp_month' => 12,
                        'exp_year' => 25,
                        'cvc' => "123",
        ]);

        $token = PaymongoFacade::token()->find($createdToken->id);

        $this->assertEquals($createdToken, $token);
    }
}
