# Telemedicine app

## Anweisung zur Installation

1. erstelle einen Ordner und entpacke dort die Zipdatei


2. Führe folgende Befehle nacheinander aus.
```md 
1. docker-compose up -d
2. docker-compose exec php composer install
3. docker-compose exec php php create-schema.php
4. docker-compose exec -T mysql mysql -u telemedicine_user -psecure_password telemedicine < src/telemedicine.sql
```

3. Bei der Nutzung von Postman collection, bitte ändere das Variable `base_url`