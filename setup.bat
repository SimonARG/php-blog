@echo off
echo Creating .env file...

(
echo DB="mysql"
echo DB_HOST=
echo DB_NAME=
echo DB_USER=
echo DB_PASS=
echo DB_CHARSET=
) > .env

echo .env file created successfully.
echo Please open the .env file and fill in the database details.
pause