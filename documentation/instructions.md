# Instructions

This project can be run on either bare-metal or containerized via docker.

## Run with Docker

### Prerequisites
Please ensure you have Docker installed on your local machine

### Installation
To build and run the Docker container for this project, follow these steps:

1. Clone the repository to your local machine:
    ```
    git clone git@github.com:LucianNiculescu/Jadu.git
    ```

2. Navigate to the project directory:
    ```
    cd Jadu
    ```

3. Build the Docker container:
    ```
    docker-compose build
    ```

4. Start the Docker container using docker-compose (-d is optional, if we want to add it as a daemon):
    ```
    docker-compose up -d
    ```

## Run locally
If we don't want to use Docker, we can build it manually:

### Prerequisites
Please make sure you have the following installed on your local machine:
- PHP >= 8.1;
- Apache web server configured;
- Composer;

### Installation
To build and run the Docker container for this project, follow these steps:

1. Clone the repository to your local machine:
    ```
    git clone git@github.com:LucianNiculescu/Jadu.git
    ```

2. Navigate to the project directory:
    ```
    cd Jadu
    ```

3. Install dependencies using Composer:
    ```
    composer install
    ```

4. Set up Apache configuration:
    - Copy the `apache.conf` file to your Apache configuration directory;
    - Update the Apache configuration to link to your project directory;
    - Ensure URL rewriting is enabled;

5. Start Apache web server:
    - Start or restart Apache web server to apply the new configuration;

6. Access the application:
    1. Start the symfony web application with `symfony server:start`, additionally you can stop with `symfony server:stop`
    2. Once Apache is running, you can access the application by navigating to `http://localhost:8080` in your web browser. You should be welcomed by a default symfony landing page. This application was designed only as an API and as such, does not offer a friendly interface as it was not within scope of the exercise.

## Usage
Once the application is running, you can interact with it using an API development tool like Insomnia or Postman. For this exercise, I have used Postman and exported a collection that can be found under the repository  [jadu.postman_collection.json](/jadu.postman_collection.json)
Each Request Response will include a JSON Payload that will include:
1. `message`: A string that will indicate the description of the query
2. `success`: A boolean value indicating the result of the query

### Palindrome
POST `http://{{host}}:{{port}}/validate/palindrome` - The provided JSON Body should include a "word" key.

1. Using the index `word` with the value of `anna`
```
{
    "word": "anna"
}
```

> {
"message": "The word: 'anna' is a palindrome.",
"success": true
}

2. Using the index `word` with the value of `Bark`
```
{
    "word": "Bark"
}
```

> {
"message": "The word: 'Bark' is NOT a palindrome.",
"success": false
}

3. Using the index `words` with value of anna

```
{
    "words": "anna"
}
```
> {
"message": "Please ensure you provide a 'word' key index.",
"success": false
}

### Anagram
POST  `http://{{host}}:{{port}}/validate/anagram` - The provided JSON body should include the keys: "word" and "comparison"

```
{
    "word": "coalface",
    "comparison": "cacao elf"
}
```

> {
"message": "The word: 'coalface' is an anagram of 'cacao elf'.",
"success": true
}


```
{
    "word": "coalface",
    "comparison": "dark elf"
}
```

> {
"message": "The word: 'coalface' is NOT an anagram of 'dark elf'.",
"success": false
}

### Pangram
POST  `http://{{host}}:{{port}}/validate/pangram` - The provided JSON body should include a "phrase" key.

```
{
    "phrase": "The quick brown fox jumps over the lazy dog"
}
```

> {
"message": "The phrase: 'The quick brown fox jumps over the lazy dog' is a pangram.",
"success": true
}


```
{
    "phrase": "The British Broadcasting Corporation (BBC) is a British public service broadcaster."
}
```

> {
"message": "The phrase: 'The British Broadcasting Corporation (BBC) is a British public service broadcaster.' is NOT a pangram.",
"success": false
}

## Testing
All test cases are grouped as either `unit` or `integration`. Both types of tests can be run sequentially via:
```
php bin/phpunit
``` 
### Unit tests
If you want to just execute unit tests:
```
php bin/phpunit --group unit
```
### Integration tests
If you want to just execute integration tests:
```
php bin/phpunit --group integration
```
