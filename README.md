# JSON Decoder
Json Decoder into class

## First step

```php
use Thevenrex\JsonToClass\JsonToClassDecoder;
```

## Usage

Create Class with public methods to used in json same

```php
class User {
	public int $id;
	public string $name;
	public string $username;
}
```

### Create a new instance of JSON class with json raw

```php
$json = '{
    "id": 1,
    "name": "John Doe",
    "username": "johndoe"
}';
$decoder = new JsonToClassDecoder($rawJson);

```
### Decode content

```php
$user = new User();
$decoder->decode($user);
```

The attributes is changed with json Datas
```php
var_dump($u);
```

example output:
```plaintext
class User#3 (3) {
  public int $id =>
  int(1)
  public string $name =>
  string(5) "John Doe"
  public string $username =>
  string(10) "johndoe"
}
```