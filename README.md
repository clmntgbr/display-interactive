**_This is test project for Display Interactive_**

**_How to make it works_**

`git clone https://github.com/clmntgbr/display-interactive.git`

*Change DATABASE_URL in env file*

`composer install`

`bin/console doctrine:database:create`

`bin/console doctrine:migration:migrate`

`bin/console doctrine:fixture:load`

**_For inserting CSV file_**
`bin/console app:read-csv`

**_For Sending Purchase JSON_**
`bin/console app:send-purchases`