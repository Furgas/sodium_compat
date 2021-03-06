<?php

class Blake2bTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        ParagonIE_Sodium_Compat::$disableFallbackForUnitTests = true;
    }

    /**
     * @covers ParagonIE_Sodium_Compat::crypto_generichash()
     */
    public function testGenericHash()
    {
        $this->assertSame(
            pack('H*', 'df654812bac492663825520ba2f6e67cf5ca5bdc13d4e7507a98cc4c2fcc3ad8'),
            ParagonIE_Sodium_Compat::crypto_generichash('Paragon Initiative Enterprises, LLC'),
            'Chosen input.'
        );
    }

    /**
     * @covers ParagonIE_Sodium_Compat::crypto_generichash_init()
     * @covers ParagonIE_Sodium_Compat::crypto_generichash_update()
     * @covers ParagonIE_Sodium_Compat::crypto_generichash_final()
     */
    public function testGenericHashStream()
    {
        $ctx = ParagonIE_Sodium_Compat::crypto_generichash_init();
        ParagonIE_Sodium_Compat::crypto_generichash_update($ctx, 'Paragon Initiative ');
        ParagonIE_Sodium_Compat::crypto_generichash_update($ctx, 'Enterprises, LLC');
        $this->assertSame(
            'df654812bac492663825520ba2f6e67cf5ca5bdc13d4e7507a98cc4c2fcc3ad8',
            bin2hex(ParagonIE_Sodium_Compat::crypto_generichash_final($ctx)),
            'Chosen input.'
        );
    }
}
