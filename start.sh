#!/bin/bash

function start_php_server() {
    echo "Démarrage serveur php ..."
    php -S localhost:8080 -t app/public/
}

{
    start_php_server
} || {
    echo "Installer  php?"
    read res
    if [ $res = "y" ]; then
        echo "Installation php ..."
        sudo apt install php
        echo "Installation php terminée"

        start_php_server
    else
        echo "Abandon"
    fi
}
