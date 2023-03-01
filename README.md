# Guide d'installation : Outil de planification des parcours de soins
*Ce fichier est un .md, c'est à dire qu'il est rédigé en markdown et nécéssite d'être ouvert comme tel, et non pas dans un bloc-note.*

| Version | Date       | Auteur           |
| ------- | ---------- | ---------------- |
| 1.0     | 2023.03.01 | BLUMSTEIN Thomas |

## Sommaire
<!-- TOC -->
- [Guide d'installation : Outil de planification des parcours de soins](#guide-dinstallation--outil-de-planification-des-parcours-de-soins)
  - [Sommaire](#sommaire)
  - [Prérequis](#prérequis)
  - [Installation des prérequis](#installation-des-prérequis)
    - [1. Php](#1-php)
    - [2. Composer](#2-composer)
    - [3. Scoop](#3-scoop)
    - [4. NodeJS](#4-nodejs)
    - [5. Symfony](#5-symfony)
  - [Compilation du projet](#compilation-du-projet)
    - [1. Clonage du projet](#1-clonage-du-projet)
    - [2. Lancement du projet](#2-lancement-du-projet)
<!-- TOC -->

## Prérequis

- [Php](https://windows.php.net/download) - version 7.4, threadsafe
- [Composer](https://getcomposer.org/download/) - pas de version précise requise
- [Scoop](https://scoop.sh) - Installation via le terminal, pas de version précise requise
- [NodeJS](https://nodejs.org/en/download/) - version 16.x
- [Symfony](https://symfony.com/download) - pas de version précise requise

## Installation des prérequis

Pour la mise en place du projet, plusieurs prérequis sont nécessaires à l'installation. Ils sont listés ci-dessus, et le
détail de l'installation ainsi que les résolutions d'erreurs possibles se trouvent ci-dessous.

### 1. Php

Les librairies utilisées dans le projet nécessitent une version de Php antérieure à la version 8.0.0.

- Rendez-vous sur le site de [Php](https://windows.php.net/download)
- Téléchargez la version 7.4.0 ou toute autre version antérieure, en faisant bien attention à télécharger la version
  **_threadsafe_**.  
![php version](https://i.imgur.com/p4GX16I.png)
- Dézippez le dossier .zip téléchargé. La destination et le nom de ce dossier ne sont pas importants, mais il est
  conseillé de le placer dans un dossier facilement accessible.
- Ajoutez le chemin vers le dossier tout juste extrait à la variable d'environnement *Path*.
- Dupliquez le fichier `php.ini-development`et renommez le `php.ini` en changeant son extension. 
- Ouvrez php.ini avec un éditeur de texte, et décommentez les lignes :
- `extension_dir = "ext"`
- `extension=pdo_sqlite`
- Tapez `var` dans la barre de recherche du pc et sélectionnez l'option "*Modifier les variables
  d'environnement système*"  
  ![var](https://i.imgur.com/PLollpn.png)
  - Dans la fenêtre qui s'ouvre, cliquez sur "*Variables d'environnement*" dans l'onglet "*Paramètres système
    avancés*" puis sur "*Variables d'environnement*"
    
    ![var2](https://i.imgur.com/z3Wz1gh.png)
  - Dans la fenêtre du bas "variable **système**", sélectionnez la variable **Path** et cliquez sur "*Modifier*"
  - Cliquez sur "*Nouveau*" pour ajouter un nouveau chemin à cette variable d'environnement, puis sur "*Parcourir*"
  - Sélectionnez le dossier tout juste extrait et cliquez sur "*OK*"
  - Validez en cliquant sur "*OK*" pour fermer les fenêtres une à une.
- Ouvrez un terminal en tapant `cmd` dans la barre de recherche du pc et tapez la commande `php -v` pour vérifier
  que la version de Php installée est bien **antérieure à la version 8.0.0**. Si ce n'est pas le cas, recommencez les
  étapes précédentes en téléchargeant une version antérieure à la version 8.0.0.  
  ![php-v](https://i.imgur.com/gxm1Byc.png)  

> Si le terminal affiche "command not found", essayez de fermer le terminal et de recommencer sur un nouveau.

### 2. Composer

Nous allons ensuite avoir besoin de Composer pour installer les différentes librairies.

- Rendez-vous sur le site de [Composer](https://getcomposer.org/download/)
- Cliquez sur le lien "*Composer-Setup.exe*" pour télécharger l'installer  
  ![composerdl](https://i.imgur.com/swq3YA6.png)
- Conservez la configuration par défaut
    - Ne pas cocher Developper Mode
    - Vérifier que le chemin vers l'executable php est le bon
    - Ne rien renseigner ou cocher dans la section Proxy
    - Cliquer sur "*Install*"
- Ouvrez un terminal en tapant `cmd` dans la barre de recherche du pc et tapez la commande `composer` pour vérifier
  que l'installation s'est bien déroulée. Il devrait alors apparaître ce dessin ainsi qu'une liste de commandes
  possibles :  
  ![composer](https://i.imgur.com/PwXaqei.png)  

> Si le terminal affiche "command not found", essayez de fermer le terminal et de recommencer sur un nouveau.

### 3. Scoop
- Ouvrez un terminal et tapez : 
- ` set-ExecutionPolicy RemoteSigned -scope CurrentUser`
- `iwr -useb get.scoop.sh | iex`
- Si vous obtenez : "Running the installer as administrator is disabled by default, see https://github.com/ScoopInstaller/Install#for-admin for details." tapez :
  -  `iwr -useb get.scoop.sh -outfile 'install.ps1'`
  -  `.\install.ps1 -RunAsAdmin`


### 4. NodeJS

La phase suivante concerne l’installation de NodeJS qui nous servira par la suite pour la compilation
de nos ressources graphiques.

- Rendez-vous sur le site de [NodeJS](https://nodejs.org/en/download/)
- Cliquez sur le logo Windows Installer dans la section LTS pour télécharger l'installer  
  ![nodejsdl](https://i.imgur.com/jRV3YwV.png)
- Lancez le .msi téléchargé
    - Le chemin d'installation n'est pas très important
    - Vérifiez que le symbole à côté de "*Add to PATH*" est bien le même que pour les autres champs
    - Il n'y a pas besoin d'installer les outils nécessaires et Chocolatey
- Ouvrez un terminal en tapant `cmd` dans la barre de recherche du pc et tapez la commande `node -v` pour vérifier
  que l'installation s'est bien déroulée. Il devrait alors apparaître la version de node téléchargée :  
  ![node-v](https://i.imgur.com/vpGMPFT.png)  

> Si le terminal affiche "command not found", essayez de fermer le terminal et de recommencer sur un nouveau.

### 5. Symfony
- Ouvrez un terminal et tapez : 
- ` scoop install symfony-cli`
- `symfony local:php:list -vvv`
- Vérifiez que votre version php choisie est bien surlignée, et pas une autre
- Si ce n'est pas le cas, changez la variable d'environnement de PHP en vous référent au paragraphe [Php](#1-php)


## Compilation du projet

### 1. Clonage du projet

Le code source du projet est disponible sur le dépôt [Github](https://github.com/Oute-Oute/PRD).

> - Pensez à bien vous placer sur la branche où se trouve le code vous interessant : [Import-Export](https://github.com/Oute-Oute/PRD/tree/Import-Export) pour l'outil de planification ou [Test-Tool](https://github.com/Oute-Oute/PRD/tree/Tests_Tool) pour l'outil d'ordonnancement

- Dans un terminal, déplacez vous dans le dossier du projet (par exemple `cd C:[User]\PRD`) 
- Changez votre version de php : `echo [votre-version-php] > .php-version` (ex : `echo 7.4.0 > .php-version`)


### 2. Lancement du projet

- Dans un terminal ouvert dans le dossier du projet tapez la commande `symfony server:start` pour démarrer le serveur  
 ![symfonyok](https://i.imgur.com/W4tn9Xj.png)  

> Si cette erreur s'affiche:  
> ![symfonyko](https://i.imgur.com/EcyjK3U.png)  
> Il faut alors installer symfony en allant sur le site [Symfony](https://symfony.com/download)
> - Ajoutez le dossier d'extraction de symfony à la variable d'environnement Path si ce n'est pas fait
> - Tapez la commande `symfony -V` pour vérifier que l'installation s'est déroulée sans problème (la majuscule est
importante)

- Ouvrez un navigateur et tapez l'adresse inscrite dans l'encadré vert (ici http://127.0.0.1:8000)
- Si tout s'est bien déroulé, la page de connexion du projet devrait s'afficher et vous pourrez vous connecter.