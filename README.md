## General consideration about the test

I have to admit that I never used Redis or GraphQL. But I love challenges and
learning something new! So, let's play!

## Api client

A swagger web client is added to make it easy to describe and test the API.
The YAML file is located in the `\Doc` sub-folder and mounted in Docker.

Please, open the following url: `http://localhost:8080/`

The remote API make use also of sub breed. They aren't covered by the spec,
but they are parsed and stored for future use.

## Laravel commands

### Start Laravel Containers

The following commands should be executed from the root of the project

```
cd TopTalentForBusiness && ./vendor/bin/sail up
```

### Migrate
```
./vendor/bin/sail artisan migrate
```

### Refresh and reset the migrations
```
./vendor/bin/sail artisan migrate:refresh
```
