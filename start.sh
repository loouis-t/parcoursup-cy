#!/bin/bash

function start_php_server() {
    echo "Démarrage serveur php ..."
    php -S localhost:8080 -t app/public/
}


function switch_branch() {
    git checkout main
    git pull origin main

    echo ""
    echo "T'es qui toi? (c pour un pote)"
    echo ""
    echo "  1. Louis      (louis)"
    echo "  2. Baptiste   (LaBrancheDuBG)"
    echo "  3. Yan        (Yan)"
    echo "  4. Ugo        (Ugo)"
    echo "  5. Edouard    (EDOUDOU)"
    echo ""
    printf "Numéro : "
    read BRANCH_NO
    echo ""

    case $BRANCH_NO in
        "1")
            BRANCH="louis";;
        "2")
            BRANCH="LaBrancheDuBG";;
        "3")
            BRANCH="Yan";;
        "4")
            BRANCH="Ugo";;
        "3")
            BRANCH="EDOUDOU";;
        *)
            echo "Branche inconnue"
            exit 0;;
    esac

    {
        git checkout -b $BRANCH
    } || {
        git branch -d $BRANCH
        git checkout $BRANCH
    }

    printf "Voulez-vous récupérer les modifications de votre branche ($BRANCH)? (y/n) : "
    read RECUPERER_BRANCH
    if [[ "$RECUPERER_BRANCH" == "y" ]]; then
        git pull origin $BRANCH
    fi
}

switch_branch
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
