RC-Like
========

Simple system to create a study and data capture information front-end. This project explores what is possible right now using HTML5. This is a single-page application with three areas. The IMPORTER supports data uploads using drag&drop as csv-files. The STORE shows the currently existing data. The DESIGNER displays the data dictionary. All data is displayed as tables and table entries can be edited in-place. New data can be entered directly.

Screenshots
-----------

<img src="/img/snapshot.png"></img>

<img src="/img/CreateProject.png"></img>


Use cases
----------
Projects that capture data from N sites. Each site shall handle their own data. Data from different sites shall be compatible/merged with one another.

Given some existing spreadsheet with data create a data dictionary that describes that data.

Verify existing spreadsheets against existing data dictionaries.

Data representation
-------------------
Data descriptions are stored as a data dictionary that lists the data measures, references, descriptions and types etc. Data is stored in a seperate structure and can be changed independently.

All data is stored as NO-SQL database suitable for sparse data structures (native json file store).


Debug
======

In order to test the page its easiest to start a local php web-server and to connect to the interface on http://localhost:8000.

```
php -S localhost:8000
```
