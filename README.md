# AgenceSymfony 
### Projet personnel - Web app Agence immobilière avec Symfony 4.4
### Environnement: Php7, Symfony4, mysql, webpack-encore
#### J'utilise wampserver

## 1.Installation
### -Créer un fichier .env contenant APP_ENV APP_SECRET DATABASE_URL
#### Exemple:
##### APP_ENV=dev
##### APP_SECRET=4497a1cbfc155a660c82cdce91fa477b
##### DATABASE_URL="mysql://root:root@127.0.0.1:3306/masuperagence?serverVersion=8&charset=utf8mb4"
### -Terminal: composer install
### -Terminal: npm install

## 2.Créer base des données:
### php bin/console d:d:c

## 3. Créer les tables
### php bin/console d:m:m

## 4.Lancer le programme
### -Terminal: npm run dev-server
### -Terminal: symfony serve:start -d

## 5.Visiter le site local 
### -localhost 127.0.0.1:8000

## 6.Créer un utilisateur dans la tableau user:
### Exemple:
#### nom: hugo
#### password: 12345678

## 7. Connecter sur ce site en entrant le nom et le password créé précédemment
## 8. Edit un nouveau bien



