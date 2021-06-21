# COVID PASS

This tool parses official EU COVID-19 Certificates issued as proof of vaccination or test. Unfortunately, I don't have any certificates that proove that I had covid thus I can't check if those work as well. 

This tool currently supports:

* Vaccination certifcates
* Test certificates by:
  * MA15 Gesundheitsdienst der Stadt Wien
  * LifeBrain
  * Rotes Kreuz LifeBrain GmbH
  * Niederösterreich Testet

other test centres would propably work but i suspect that some minor tweaking is needed as the code for data extraction relies on line numbers.

Feel free to submit changes to reflect those certificates! 
## Installation

Clone the git repo to your server and install the composer dependencies with
```bash
composer install
```
afterwards, please copy over the .env.example file and fill the fields.

The webroot of your server has to be set to the \web directory.

## IMPORTANT NOTE
Please follow the usage part of https://github.com/flexible-agency/php-pkpass to generate your Apple Certificate file. Without this file, this script won't be able to generate Pass files. 

## Usage
Upload certificate, download passfile. 

## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

Please make sure to update tests as appropriate.

## License
[Unlicense](https://unlicense.org/)