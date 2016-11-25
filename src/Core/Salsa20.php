<?php

class ParagonIE_Sodium_Core_Salsa20 extends ParagonIE_Sodium_Core_Util
{
    const ROUNDS = 20;

    /**
     * Calculate an salsa20 hash of a single block
     *
     * @param string $in
     * @param string $k
     * @param string|null $c
     * @return string;
     */
    public static function core_salsa20($in, $k, $c = null)
    {
        if ($c === null) {
            $j0  = $x0  = 0x61707865;
            $j5  = $x5  = 0x3320646e;
            $j10 = $x10 = 0x79622d32;
            $j15 = $x15 = 0x6b206574;
        } else {
            $j0  = $x0  = self::load_4(self::substr($c,  0, 4));
            $j5  = $x5  = self::load_4(self::substr($c,  4, 4));
            $j10 = $x10 = self::load_4(self::substr($c,  8, 4));
            $j15 = $x15 = self::load_4(self::substr($c, 12, 4));
        }
        $j1  = $x1  = self::load_4(self::substr($k,  0, 4));
        $j2  = $x2  = self::load_4(self::substr($k,  4, 4));
        $j3  = $x3  = self::load_4(self::substr($k,  8, 4));
        $j4  = $x4  = self::load_4(self::substr($k, 12, 4));
        $j11 = $x11 = self::load_4(self::substr($k, 16, 4));
        $j12 = $x12 = self::load_4(self::substr($k, 20, 4));
        $j13 = $x13 = self::load_4(self::substr($k, 24, 4));
        $j14 = $x14 = self::load_4(self::substr($k, 28, 4));
        $j6  = $x6  = self::load_4(self::substr($in, 0, 4));
        $j7  = $x7  = self::load_4(self::substr($in, 4, 4));
        $j8  = $x8  = self::load_4(self::substr($in, 8, 4));
        $j9  = $x9  = self::load_4(self::substr($in, 12, 4));

        for ($i = self::ROUNDS; $i > 0; $i -= 2) {
            $x4 ^= self::rotate($x0 + $x12, 7);
            $x8 ^= self::rotate($x4 + $x0, 9);
            $x12 ^= self::rotate($x8 + $x4, 13);
            $x0 ^= self::rotate($x12 + $x8, 18);
            $x9 ^= self::rotate($x5 + $x1, 7);
            $x13 ^= self::rotate($x9 + $x5, 9);
            $x1 ^= self::rotate($x13 + $x9, 13);
            $x5 ^= self::rotate($x1 + $x13, 18);
            $x14 ^= self::rotate($x10 + $x6, 7);
            $x2 ^= self::rotate($x14 + $x10, 9);
            $x6 ^= self::rotate($x2 + $x14, 13);
            $x10 ^= self::rotate($x6 + $x2, 18);
            $x3 ^= self::rotate($x15 + $x11, 7);
            $x7 ^= self::rotate($x3 + $x15, 9);
            $x11 ^= self::rotate($x7 + $x3, 13);
            $x15 ^= self::rotate($x11 + $x7, 18);
            $x1 ^= self::rotate($x0 + $x3, 7);
            $x2 ^= self::rotate($x1 + $x0, 9);
            $x3 ^= self::rotate($x2 + $x1, 13);
            $x0 ^= self::rotate($x3 + $x2, 18);
            $x6 ^= self::rotate($x5 + $x4, 7);
            $x7 ^= self::rotate($x6 + $x5, 9);
            $x4 ^= self::rotate($x7 + $x6, 13);
            $x5 ^= self::rotate($x4 + $x7, 18);
            $x11 ^= self::rotate($x10 + $x9, 7);
            $x8 ^= self::rotate($x11 + $x10, 9);
            $x9 ^= self::rotate($x8 + $x11, 13);
            $x10 ^= self::rotate($x9 + $x8, 18);
            $x12 ^= self::rotate($x15 + $x14, 7);
            $x13 ^= self::rotate($x12 + $x15, 9);
            $x14 ^= self::rotate($x13 + $x12, 13);
            $x15 ^= self::rotate($x14 + $x13, 18);
        }

        $x0  += $j0;
        $x1  += $j1;
        $x2  += $j2;
        $x3  += $j3;
        $x4  += $j4;
        $x5  += $j5;
        $x6  += $j6;
        $x7  += $j7;
        $x8  += $j8;
        $x9  += $j9;
        $x10 += $j10;
        $x11 += $j11;
        $x12 += $j12;
        $x13 += $j13;
        $x14 += $j14;
        $x15 += $j15;

        return self::store_4($x0) .
            self::store_4($x1) .
            self::store_4($x2) .
            self::store_4($x3) .
            self::store_4($x4) .
            self::store_4($x5) .
            self::store_4($x6) .
            self::store_4($x7) .
            self::store_4($x8) .
            self::store_4($x9) .
            self::store_4($x10) .
            self::store_4($x11) .
            self::store_4($x12) .
            self::store_4($x13) .
            self::store_4($x14) .
            self::store_4($x15);
    }

    /**
     * @param int $len
     * @param string $nonce
     * @param string $key
     * @return string
     */
    public static function salsa20($len, $nonce, $key)
    {
        $kcopy = '' . $key;
        $in = self::substr($nonce, 0, 8) . str_repeat("\0", 8);
        $c = '';
        while ($len >= 64) {
            $c .= self::core_salsa20($in, $kcopy, null);
            $u = 1;
            for ($i = 8; $i < 16; ++$i) {
                $u += $in[$i];
                $u >>= 8;
            }
            $len -= 64;
        }
        if ($len > 0) {
            $c .= self::substr(
                self::core_salsa20($in, $kcopy, null),
                0,
                $len
            );
        }
        ParagonIE_Sodium_Compat::memzero(&$kcopy);
        return $c;
    }

    /**
     * @param int $u
     * @param int $c
     * @return int
     */
    protected static function rotate($u, $c)
    {
        if (PHP_INT_SIZE === 8) {
            return 0xffffffff & (($u << $c) | ($u >> (64 - $c)));
        }
        return ($u << $c) | ($u >> (32 - $c));
    }
}