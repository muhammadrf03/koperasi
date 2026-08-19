<?php

namespace App\Support;

class UrlCodec
{
    private const MASK = 0x7FFFFFFF;

    private const MULT = 0x2F4F6F1;

    private const INV = 878262801;

    private const XOR_KEY = 0x0A9B3C5D;

    private const FILLER_MULT = 0x1F123BB;

    private const FILLER_ADD = 0x0DEAD;

    private const FILLER_MOD = 144555105949057024;

    private const CHARS = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';

    private const LENGTH = 16;

    private const S_BLOCK = 6;

    private const F_BLOCK = 10;

    public static function encode(int $id): string
    {
        $s = ((($id ^ self::XOR_KEY) & self::MASK) * self::MULT) & self::MASK;
        $f = ($s * self::FILLER_MULT + self::FILLER_ADD) % self::FILLER_MOD;

        return str_pad(self::toBase52($f), self::F_BLOCK, 'A', STR_PAD_LEFT)
            . str_pad(self::toBase52($s), self::S_BLOCK, 'A', STR_PAD_LEFT);
    }

    public static function decode(string $value): ?int
    {
        if (strlen($value) !== self::LENGTH) {
            return null;
        }

        $f = self::fromBase52(substr($value, 0, self::F_BLOCK));
        $s = self::fromBase52(substr($value, self::F_BLOCK, self::S_BLOCK));

        if ($f === null || $s === null || $s > self::MASK) {
            return null;
        }

        if (($s * self::FILLER_MULT + self::FILLER_ADD) % self::FILLER_MOD !== $f) {
            return null;
        }

        $id = ((($s * self::INV) & self::MASK) ^ self::XOR_KEY) & self::MASK;

        return self::encode($id) === $value ? $id : null;
    }

    private static function toBase52(int $n): string
    {
        if ($n === 0) {
            return 'A';
        }

        $result = '';

        while ($n > 0) {
            $result = self::CHARS[$n % 52] . $result;
            $n = intdiv($n, 52);
        }

        return $result;
    }

    private static function fromBase52(string $value): ?int
    {
        if ($value === '') {
            return null;
        }

        $n = 0;

        foreach (str_split($value) as $char) {
            $pos = strpos(self::CHARS, $char);

            if ($pos === false) {
                return null;
            }

            $n = $n * 52 + $pos;
        }

        return $n;
    }
}
