<?php

namespace Test\Unit;

use Test\TestCase;

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
        $this->assertEquals('cc83646f6783676f6483636174', $encoded->toString('hex'));
        $this->assertEquals(13, $encoded->length());

        $encoded = $rlp->encode(['0xabcd', '0xdeff', '0xaaaa']);
        $this->assertEquals('c982abcd82deff82aaaa', $encoded->toString('hex'));
        $this->assertEquals(10, $encoded->length());
    }

    /**
     * testDecode
     * 
     * @return void
     */
    public function testDecode()
    {
        $rlp = $this->rlp;
        $encoded = '0x' . $rlp->encode(['dog', 'god', 'cat'])->toString('hex');
        $decoded = $rlp->decode($encoded);
        $this->assertEquals(3, count($decoded));
        $this->assertEquals('dog', $decoded[0]->toString('utf8'));
        $this->assertEquals('god', $decoded[1]->toString('utf8'));
        $this->assertEquals('cat', $decoded[2]->toString('utf8'));

        $encoded = '0x' . $rlp->encode(['0xabcd', '0xdeff', '0xaaaa'])->toString('hex');
        $decoded = $rlp->decode($encoded);
        $this->assertEquals(3, count($decoded));
        $this->assertEquals('abcd', $decoded[0]->toString('hex'));
        $this->assertEquals('deff', $decoded[1]->toString('hex'));
        $this->assertEquals('aaaa', $decoded[2]->toString('hex'));
    }
}