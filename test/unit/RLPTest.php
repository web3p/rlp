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