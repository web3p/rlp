<?php

namespace Test\Unit;

use Test\TestCase;
use Web3p\RLP\Types\Str;

class RLPTest extends TestCase
{
    /**
     * $testCases for rlp
     * 
     * @var array
     */
    protected $testCases = [
        [
            "decoded" => ["dog", "god", "cat"],
            "encoded" => "cc83646f6783676f6483636174",
            "rlpdecoded" => [
                "646f67", "676f64", "636174"
            ]
        ], [
            "decoded" => ["0xabcd", "0xdeff", "0xaaaa"],
            "encoded" => "c982abcd82deff82aaaa",
            "rlpdecoded" => ["abcd", "deff", "aaaa"]
        ], [
            "decoded" => 0,
            "encoded" => "80",
            "rlpdecoded" => ""
        ], [
            "decoded" => [],
            "encoded" => "c0",
            "rlpdecoded" => []
        ], [
            "decoded" => "0x00",
            "encoded" => "00",
            "rlpdecoded" => "00"
        ], [
            "decoded" => "0x0400",
            "encoded" => "820400",
            "rlpdecoded" => "0400"
        ], [
            "decoded" => [[], [[]], [ [], [[]] ]],
            "encoded" => "c7c0c1c0c3c0c1c0",
            "rlpdecoded" => [[], [[]], [ [], [[]] ]]
        ]
    ];

    /**
     * testEncode
     * 
     * @return void
     */
    public function testEncode()
    {
        $rlp = $this->rlp;

        foreach ($this->testCases as $testCase) {
            $encoded = $rlp->encode($testCase["decoded"]);
            $this->assertEquals($testCase["encoded"], $encoded);
        }
    }

    /**
     * testDecode
     *
     * @return void
     */
    public function testDecode()
    {
        $rlp = $this->rlp;
        foreach ($this->testCases as $testCase) {
            $decoded = $rlp->decode("0x" . $testCase["encoded"]);
            $this->assertEquals($testCase["rlpdecoded"], $decoded);
        }
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
            $encoded = $rlp->encode($test["in"]);

            $this->assertEquals($test["out"], $encoded);
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
        $this->assertEquals("c0", $rlp->encode([]));
        $this->assertEquals("80", $rlp->encode(0));
        $this->assertEquals("80", $rlp->encode(0x0));
        $this->assertEquals("80", $rlp->encode(-1));
        $this->assertEquals("80", $rlp->encode(-2));
        $this->assertEquals("30", $rlp->encode("0"));
        $this->assertEquals("00", $rlp->encode("0x0"));
        $this->assertEquals("80", $rlp->encode(null));
    }

    /**
     * testLongList
     * Lists with payload >= 56 bytes (prefix 0xf8-0xff) followed by siblings.
     *
     * @return void
     */
    public function testLongList()
    {
        $rlp = $this->rlp;
        $longList = array_fill(0, 56, 'a');
        $longListDecoded = array_fill(0, 56, '61');
        // f838 + 56 x 61
        $longListEncoded = 'f838' . str_repeat('61', 56);

        $testCases = [
            [
                // [[56 x 'a'], 'b']: payload 58 + 1 = 59 bytes
                "decoded" => [$longList, 'b'],
                "encoded" => 'f83b' . $longListEncoded . '62',
                "rlpdecoded" => [$longListDecoded, '62']
            ], [
                // [[56 x 'a'], [56 x 'a']]: payload 58 + 58 = 116 bytes
                "decoded" => [$longList, $longList],
                "encoded" => 'f874' . $longListEncoded . $longListEncoded,
                "rlpdecoded" => [$longListDecoded, $longListDecoded]
            ], [
                // ['b', [56 x 'a'], 'c']: payload 1 + 58 + 1 = 60 bytes
                "decoded" => ['b', $longList, 'c'],
                "encoded" => 'f83c62' . $longListEncoded . '63',
                "rlpdecoded" => ['62', $longListDecoded, '63']
            ]
        ];

        foreach ($testCases as $testCase) {
            $this->assertEquals($testCase["encoded"], $rlp->encode($testCase["decoded"]));
            $this->assertEquals($testCase["rlpdecoded"], $rlp->decode("0x" . $testCase["encoded"]));
        }
    }

    /**
     * testEncodeLowBytes
     * Bytes below 0x10 must be encoded as two hex digits.
     *
     * @return void
     */
    public function testEncodeLowBytes()
    {
        $rlp = $this->rlp;

        $this->assertEquals("0f", $rlp->encode("\x0f"));
        $this->assertEquals("820102", $rlp->encode("\x01\x02"));
        $this->assertEquals("820000", $rlp->encode("\x00\x00"));
        $this->assertEquals("83610a62", $rlp->encode("a\nb"));
        $this->assertEquals("c3820102", $rlp->encode(["\x01\x02"]));

        $this->assertEquals("0102", Str::encode("\x01\x02"));
        $this->assertEquals("0102", Str::encode("\x01\x02", 'ascii'));

        // every byte value 0x00-0xff: 256-byte payload => b9 0100
        $allBytes = implode('', array_map('chr', range(0, 255)));
        $encoded = $rlp->encode($allBytes);
        $this->assertEquals("b90100" . bin2hex($allBytes), $encoded);
        $this->assertEquals(bin2hex($allBytes), $rlp->decode("0x" . $encoded));
    }

    /**
     * truncatedOrTrailingRlpProvider
     *
     * @return array
     */
    public function truncatedOrTrailingRlpProvider()
    {
        return [
            // truncated short string
            'short string missing payload' => ['81'],
            'short string missing byte' => ['83646f'],
            // truncated long string
            'long string missing length' => ['b8'],
            'long string partial length' => ['b901'],
            'long string missing byte' => ['b838' . str_repeat('61', 55)],
            'long string length 256, 10 bytes' => ['b90100' . str_repeat('61', 10)],
            // truncated short list
            'short list missing payload' => ['c3'],
            'short list missing byte' => ['c38364'],
            // child longer than its parent list
            'child overruns list' => ['c2836400'],
            'nested child overruns list' => ['c4c3836400'],
            // truncated long list
            'long list missing length' => ['f8'],
            'long list missing byte' => ['f838' . str_repeat('61', 55)],
            // trailing data after the top-level item
            'single byte + trailing' => ['0102'],
            'empty string + trailing' => ['8000'],
            'short string + trailing' => ['83646f6700'],
            'empty list + trailing' => ['c000'],
            'short list + trailing' => ['c3826162c0'],
            'long string + trailing' => ['b838' . str_repeat('61', 56) . '01'],
            'long list + trailing' => ['f838' . str_repeat('61', 56) . '01'],
        ];
    }

    /**
     * testDecodeTruncatedOrTrailingRlp
     *
     * @dataProvider truncatedOrTrailingRlpProvider
     * @param string $encoded
     * @return void
     */
    public function testDecodeTruncatedOrTrailingRlp(string $encoded)
    {
        $this->expectException(\RuntimeException::class);
        $this->rlp->decode('0x' . $encoded);
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
    //         $encoded = $rlp->encode($test["in"]);

    //         $this->assertEquals($test["out"], $encoded);
    //     }
    // }
}