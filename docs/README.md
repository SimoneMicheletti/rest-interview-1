## Requisiti

- PHP 8.2 o versione successiva
- Composer

## Inizializzazione ed utilizzo

1. Clona la repository:

    ```
    git clone <URL del repository>
    ```

2. Entra nella cartella del progetto:

    ```
    cd <nome della cartella del progetto>
    ```

3. Installa le dipendenze tramite Composer:

    ```
    composer install
    ```

4. Esegui il progetto:

    ```
    php index.php
    ```

## Struttura del Progetto

- src/: Cartella codice del progetto
- tests/: Cartella test
- cache/: Cartella per la gestione della cache
- vendor/: Cartella dipendenze gestite tramite composer
- docs/: Documentazione
- .env: File di configurazione
- index.php: File di esecuzione

## Nota sulla cache

Nel file di configurazione è possibile definire il periodo oltre il quale la cache viene invalidata.

## Sviluppatore

Simone Micheletti (sm.micheletti@gmail.com)