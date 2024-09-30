<?php

class SimpleUser
{
    public int $id;
    public string $name;
    public string $username;
}

class UserWithArray
{
    public int $id;
    public string $name;
    public string $username;
    public array $address;
}

class UserWithObject
{
    public int $id;
    public string $name;
    public string $username;
    public Product $product;
}

class Product
{
    public int $id;
    public string $name;
    public float $price;
}
