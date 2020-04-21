# Manual

```bash
composer install
```

preparing the database to work, inserting mock data
```bash
composer prepare
```

running application
```bash
composer start
```

test and sniffer

```bash
composer phpunit
composer sniffer
```

# HOW TO USE

After running "composer install", "composer prepare" and "composer start", the application should be
visible in the address 127.0.0.1:5000.

The schedule tables will only allow to edit the person.
Important: THE FIRST NAME OF THE PERSON must be used (for now at least).

Editing the person from the schedule will not reload the page.
Adding a new person, new workplace or new equipment will.

The validation is not finished, is very basic, but XSS attacks would be prevented.

To save a change you must press ENTER

On the schedule view, for the schedule table, only the person can be changed.

On the workplaces, persons and equipment you must add a new row editing the available information, then SAVING with ENTER.
After this, these fields can only be changed in their own view. (Except for workplace)
