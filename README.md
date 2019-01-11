# "TIC TAC TOE" game

The goal of the game is to align 3 pieces horizontally, vertically or diagonally. Players take turns one after the other, with "X" or "O".
Database stores in a table all games, each one with these fields:

- State of the board. For example: [["x","o","-"],["-","-","O"],["-","X","-"]]
- If it is finished or not.
- Last player in move: "X" or "O".
- Total number of movements.

## Requirements

I have used:

1. Symfony 3.4
2. PHP: 7.3.0
3. MariaDB 10.1.37
4. Apache 2.4.37

## Installation

1. Import database with file: "tre_en_raya.sql" that is in the root.
2. Import or download all files of the proyect.
3. Configure the connection with database in: app/config/parameters.yml
4. In command line go to your project path and update composer:

```
cd my_project_name/
composer update
```

More info in: [symfony docs](https://symfony.com/doc/3.4/setup.html)
