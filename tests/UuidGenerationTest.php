<?php


class UuidGenerationTest extends TestCase
{
    public function testGeneration()
    {
        $uuid = \Ramsey\Uuid\Uuid::uuid4();

        $this->assertTrue($uuid instanceof \Ramsey\Uuid\Uuid);
        $this->assertTrue(strlen($uuid->toString()) > 1);
    }
}
