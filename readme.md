# SimonARG's lightweight blogging framework
This is a PHP-based monolithic blog back-end and front-end that allows you to customize and run a blog without ever touching a single line of code.

## Installation for an Apache web server:

1. Download the project [as a .zip](https://github.com/SimonARG/php-blog/archive/refs/heads/main.zip).
2. Unzip download contents into Apache's `htdocs` folder.
3. Run `setup.bat` in order to create `.env` file.
4. Open the `.env` file and fill the values with your SQL database's information.
5. Run `seed.bat` in order to seed the initial configuration.
6. Configure your Apache server as you wish, and head to the root URL in a web browser.
7. Log-in as `email: admin@gmail.com password: admin`
8. Head to the `configuration` menu from the sidebar.
9. Configure the UI to your liking.
10. Start blogging!

## Usage:

- Only users with role `admin` can change blog configuration.
- Only users with role `moderator` or `admin` can access the **Mod Panel**
- `Admins` and `mods` can update or delete any public resource, including user profiles.
- Only users given the role `poster` by an `admin` or `mod` will be able to create blog posts.
- `Restricted` or `banned` users can see resources but not modify them, `banned` users can't even log in.