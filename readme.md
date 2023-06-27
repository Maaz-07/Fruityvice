# FruityFaves Web Application

The FruityFaves web application allows users to explore a wide variety of fruits, save their favorite fruits, and view detailed nutrition facts. The application fetches fruit data from the [Fruityvice API](https://fruityvice.com/) and stores it in a local database. Once all the fruits are saved, an email notification is sent to the admin email address.

## Features

- Fetches fruit data from the Fruityvice API and stores it in a local database
- Sends an email notification to the admin email address when all fruits are saved
- Paginated list of all fruits
- Filter fruits by name and family using a search form
- Ability to add up to 10 fruits to the favorites list
- Detailed nutrition facts for favorite fruits

## Technologies Used

- PHP: Server-side scripting language used for backend logic and API integration
- HTML/CSS: Markup and styling of the web pages
- JavaScript: Used for client-side interactions and form validation
- MySQL: Database management system for storing fruit data
- SMTP(PHPMailer): Simple Mail Transfer Protocol for sending email notifications

## Setup and Installation

1. Clone the repository or download the project files.

   ```
   git clone https://github.com/Maaz-07/Fruityvice
   ```

2. Set up a web server with PHP support (e.g., Apache, Nginx, XAMPP).
3. Create a MySQL database for storing fruit data using phpMyAdmin.
4. Update the database credentials in the relevant PHP files (`email.php`, `favoritefruits.php`) to match your database configuration.
5. Update the SMTP settings in the `email.php` file to enable email notifications and provide the admin email address(The preset email will not work because of new access settings).
6. Place the project files in the web server's document root directory.
7. Access the web application through your preferred web browser.

## Usage

1. Upon accessing the application, the script will fetch fruit data from the Fruityvice API and save it to the local database.
2. Once all fruits are saved, an email notification will be sent to the admin email address to indicate the completion.
3. Navigate to the main page to view a paginated list of all fruits from the API.
4. Use the search form to filter fruits by name and family.
5. To add a fruit to your favorites list, click the star in the extreme right of respective row. You can add up to 10 fruits.
6. To remove a fruit from your favorites list simply click the star button again.
7. Navigate to the "Favorite Fruits" page to view your selected favorite fruits and their nutrition facts.

## Contributions

Contributions to the FruityFaves web application are welcome! If you have any ideas, suggestions, or improvements, please submit a pull request or open an issue.

## License

This project is licensed under the [MEE](LICENSE).

## Acknowledgements

- The Fruityvice API (https://fruityvice.com/) for providing fruit data.
- The PHPMailer (https://github.com/PHPMailer/PHPMailer) is used for handling mail related functionalities
- The web application was developed as a Task project.
