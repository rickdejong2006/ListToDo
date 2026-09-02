# ToDo List (PHP & MySQL)

Dit project is een simpele ToDo applicatie met PHP en MySQL, de styling is uitgevoerd doormiddel van bootstrap.  
Gebruikers kunnen inloggen en hun eigen taken beheren.


## Functionaliteiten

- Inloggen met e-mail en wachtwoord. 
- Automatisch account aanmaken.  
- Taken toevoegen, bewerken en verwijderen.  
- Taken per gebruiker opgeslagen.
- Gebruik van sessions en JOIN queries.

## Benodigdheden

- XAMPP
- PHP
- MySQL

## opzetten

- Clone de repository in je htdocs map van XAMP.
- Start op xamp en start Apache en MySQL.
- Open php MyAdmin.
- Voer ListToDo.sql uit in php MyAdmin.
- Ga naar local host en open login.php of ListToDo.php.

### Tabellen
- `gebruikers` (id, email, wachtwoord).
- `doelen` (id, gebruiker_id, tekst).


**login.php**
- Logt gebruikers in.
- Maakt account aan als deze niet bestaat.
- Controleerd of het wachtwoord sterk genoeg is.

**ListToDo.php**
- Laat taken zien.
- Taken toevoegen en verwijderen.
- Taken bewerken.
- Alleen toegankelijk als je ingelogd bent.

**db.php**

- Zorgt voor verbinding met database.

**ListToDo.sql**

- Maakt database aan.
- Maakt de tabellen aan.

**bootstrap-5.3.8-dist**

- Wordt gebruikt voor styling.