<?php


declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Thevenrex\JsonToClass\{JsonException, JsonToClassDecoder};

final class JsonToClassDecoderTest extends TestCase
{
    public function testSimpleTypes()
    {

        $rawJson = '{
            "id": 1,
            "name": "John Doe",
            "username": "johndoe"
        }';

        $decoder = new JsonToClassDecoder($rawJson);
        $user = new SimpleUser();
        $decoder->decode($user);
        $this->assertEquals(1, $user->id);
        $this->assertEquals('John Doe', $user->name);
        $this->assertEquals('johndoe', $user->username);
    }

    public function testArrayUsingJson()
    {

        $rawJson = '{
            "id": 1,
            "name": "John Doe",
            "username": "johndoe",
            "address": {
                "street": "123 Main St",
                "city": "New York",
                "state": "NY",
                "zip": "10001"
            }
        }';

        $decoder = new JsonToClassDecoder($rawJson);
        $user = new UserWithArray();
        $decoder->decode($user);

        $this->assertEquals(1, $user->id);
        $this->assertEquals('John Doe', $user->name);
        $this->assertEquals('johndoe', $user->username);
        $this->assertEquals('123 Main St', $user->address['street']);
        $this->assertEquals('New York', $user->address['city']);
        $this->assertEquals('NY', $user->address['state']);
        $this->assertEquals('10001', $user->address['zip']);
    }

    public function testPrimitive(): void
    {
        $json = '{"id": 1, "name": "John Doe", "username": "johndoe"}';
        $decoder = new JsonToClassDecoder($json);
        $user = new SimpleUser();

        $decoder->decode($user);

        $this->assertEquals(1, $user->id);
        $this->assertEquals('John Doe', $user->name);
        $this->assertEquals('johndoe', $user->username);
    }

    public function testArrayInObject(): void
    {
        $json = '{
            "id": 1, 
            "name": "John Doe",
            "username": "johndoe",
            "address": {
                "street": "123 Main St",
                "city": "New York",
                "state": "NY",
                "zip": "10001"
            }
        }';

        $decoder = new JsonToClassDecoder($json);
        $user = new UserWithArray();
        $decoder->decode($user);
        $this->assertEquals('John Doe', $user->name);

    }

    public function testObjectInArray(): void
    {
        $json = '{
            "id": 1,
            "name": "John Doe",
            "username": "johndoe",
            "product":{
                "id": 1,
                "name": "iPhone",
                "price": 999.99
            }
        }';
        $decoder = new JsonToClassDecoder($json);
        $user = new UserWithObject();
        $decoder->decode($user);
        $this->assertEquals('John Doe', $user->name);
    }

    public function testInvalidType(): void
    {
        $json = '{"name": 1, "age": 32, "city": "New York"}';
        $decoder = new JsonToClassDecoder($json);
        $user = new SimpleUser();

        $this->expectException(JsonException::class);
        $decoder->decode($user);
    }

    public function testInvalidSubType(): void
    {
        $json = '{"name": "John Doe", "age": 32, "city": "New York", "address": {"street": "123 Main St", "city": 1, "state": "NY", "zip": "10001"}}';
        $decoder = new JsonToClassDecoder($json);
        $user = new UserWithArray();

        $this->expectException(JsonException::class);
        $decoder->decode($user);
    }
}
