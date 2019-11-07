<?php

namespace Test\Unit;

use Test\TestCase;
use Web3p\RLP\Types\Str;

class RLPTest extends TestCase
{
    /**
     * testEncode
     * 
     * @return void
     */
    public function testEncode()
    {
        $rlp = $this->rlp;

        $encoded = $rlp->encode(['dog', 'god', 'cat']);
        $this->assertEquals('cc83646f6783676f6483636174', $encoded);

        $encoded = $rlp->encode(['0xabcd', '0xdeff', '0xaaaa']);
        $this->assertEquals('c982abcd82deff82aaaa', $encoded);
        $this->assertEquals('00', $rlp->encode(chr(0)));
        $this->assertEquals('01', $rlp->encode(chr(1)));
    }

    /**
     * testDecode
     *
     * @return void
     */
    public function testDecode()
    {
        $rlp = $this->rlp;
        $encoded = '0x' . $rlp->encode(['dog', 'god', 'cat']);
        $decoded = $rlp->decode($encoded);
        $this->assertEquals(3, count($decoded));
        $this->assertEquals('dog', Str::decodeHex(strtoupper($decoded[0])));
        $this->assertEquals('god', Str::decodeHex($decoded[1]));
        $this->assertEquals('cat', Str::decodeHex($decoded[2]));

        $encoded = '0x' . $rlp->encode(['0xabcd', '0xdeff', '0xaaaa']);
        $decoded = $rlp->decode($encoded);
        $this->assertEquals(3, count($decoded));
        $this->assertEquals('abcd', $decoded[0]);
        $this->assertEquals('deff', $decoded[1]);
        $this->assertEquals('aaaa', $decoded[2]);

        $encoded = '0x' . $rlp->encode([199999, 1]);
        $decoded = $rlp->decode($encoded);
        $this->assertEquals(2, count($decoded));
        $this->assertEquals(199999, hexdec($decoded[0]));
        $this->assertEquals(1, hexdec($decoded[1]));

    }

    /**
     * testValidRlp
     * 
     * @return void
     */
    public function testValidRlp()
    {
        $rlp = $this->rlp;
        $rlptestJson = file_get_contents(sprintf("%s%srlptest.json", __DIR__, DIRECTORY_SEPARATOR));

        $this->assertTrue($rlptestJson !== false);
        $rlptest = json_decode($rlptestJson, true);

        foreach ($rlptest as $test) {
            $encoded = $rlp->encode($test['in']);

            $this->assertEquals($test['out'], $encoded);
        }
    }

    /**
     * testIssue14
     * See: https://github.com/web3p/rlp/issues/14
     * You can find test in: https://github.com/ethereum/wiki/wiki/RLP#examples
     * 
     * @return void
     */
    public function testIssue14()
    {
        $rlp = $this->rlp;
        $this->assertEquals('c0', $rlp->encode([]));
        $this->assertEquals('80', $rlp->encode(0));
        $this->assertEquals('80', $rlp->encode(0x0));
        $this->assertEquals('80', $rlp->encode(-1));
        $this->assertEquals('80', $rlp->encode(-2));
        $this->assertEquals('30', $rlp->encode('0'));
        $this->assertEquals('00', $rlp->encode('0x0'));
        $this->assertEquals('80', $rlp->encode(null));
    }

    /**
     * testInvalidRlp
     * Try to figure out what invalidrlptest.json is.
     * 
     * @return void
     */
    // public function testInvalidRlp()
    // {
    //     $rlp = $this->rlp;
    //     $invalidrlptestJson = file_get_contents(sprintf("%s%sinvalidrlptest.json", __DIR__, DIRECTORY_SEPARATOR));

    //     $this->assertTrue($invalidrlptestJson !== false);
    //     $invalidrlptest = json_decode($invalidrlptestJson, true);

    //     foreach ($invalidrlptest as $test) {
    //         $encoded = $rlp->encode($test['in']);

    //         $this->assertEquals($test['out'], $encoded);
    //     }
    // }
}