<p align="center">
  <a href="http://codely.tv">
    <img src="public/img/interview.jpg" height="190px"/>
  </a>
</p>

<h2 align="center">
  💼 RecrutementRH
</h2>
<p align="center">
  <strong>La plateforme numérique</strong> qui permet de recruter efficacement et rapidement nos futurs consultants
  <br />
  sous le framework <strong>Symfony 5.2 🎼</strong>
  <br />
  <br />
  <a href="http://jobinterview.epizy.com/"> 📺 Voir la démo </a>
</p>

## 📌Langages et outils:

<div align="center">
<img align="left" alt="Visual Studio Code" width="26px" src="https://raw.githubusercontent.com/github/explore/80688e429a7d4ef2fca1e82350fe8e3517d3494d/topics/symfony/symfony.png" />
<img align="left" alt="Visual Studio Code" width="26px" src="https://raw.githubusercontent.com/github/explore/80688e429a7d4ef2fca1e82350fe8e3517d3494d/topics/visual-studio-code/visual-studio-code.png" />
<img align="left" alt="HTML5" width="26px" src="https://raw.githubusercontent.com/github/explore/80688e429a7d4ef2fca1e82350fe8e3517d3494d/topics/html/html.png" />
<img align="left" alt="CSS3" width="26px" src="https://raw.githubusercontent.com/github/explore/80688e429a7d4ef2fca1e82350fe8e3517d3494d/topics/css/css.png" />
<img align="left" alt="Sass" width="26px" src="https://raw.githubusercontent.com/github/explore/80688e429a7d4ef2fca1e82350fe8e3517d3494d/topics/sass/sass.png" />
<img align="left" alt="JavaScript" width="26px" src="https://raw.githubusercontent.com/github/explore/80688e429a7d4ef2fca1e82350fe8e3517d3494d/topics/javascript/javascript.png" />
<img align="left" alt="SQL" width="26px" src="https://raw.githubusercontent.com/github/explore/80688e429a7d4ef2fca1e82350fe8e3517d3494d/topics/sql/sql.png" />
<img align="left" alt="MySQL" width="26px" src="https://raw.githubusercontent.com/github/explore/80688e429a7d4ef2fca1e82350fe8e3517d3494d/topics/mysql/mysql.png" />
<img align="left" alt="Git" width="26px" src="https://raw.githubusercontent.com/github/explore/80688e429a7d4ef2fca1e82350fe8e3517d3494d/topics/git/git.png" />
<img align="left" alt="GitHub" width="26px" src="https://raw.githubusercontent.com/github/explore/78df643247d429f6cc873026c0622819ad797942/topics/github/github.png" />
<img align="left" alt="Terminal" width="26px" src="https://raw.githubusercontent.com/github/explore/80688e429a7d4ef2fca1e82350fe8e3517d3494d/topics/terminal/terminal.png" />

</div>
<br />

## 🚀 Installation et configuration:

### 🛠️ Les pré-requis techniques 

1. Installez WampServer [lien](https://www.wampserver.com/) - Si vous rencontrez une erreur de type ` MSVCR120.dll is missing ` voici un tuto vous aider à installer le serveur et résoudre ce problème : [tutoriel](https://www.youtube.com/watch?v=trPjbiGRw6w).
  💡 Si vous êtes sur Linux ou Mac ça sera mieux d’utiliser [XAMPP](https://www.apachefriends.org/fr/download.html) ou [MAMP](https://www.mamp.info/en/downloads/) 

2. [Installer Composer](https://getcomposer.org/download/) ⚠️ pensez à bien choisir la version PHP 7.4 pour composer lors de l'installation.
3. [installer Symfony CLI ](https://symfony.com/download)
4. Pour verifier si votre ordinateur répond à toutes les exigences, ouvrez votre terminal et exécutez cette commande: ` symfony check:requirements `

### 🏁 Les étapes d'installation

1. Clonez le dépot où vous voulez: `git clone --single-branch --branch master https://github.com/AsmaaZd/gestionRH`
2. Placez vous dans le dossier du projet gestionRH `cd gestionRH`
2. Installez les dépendances  : `composer install`
3. Créez la base de données : ` php bin/console doctrine:database:create `
4. Lancez les migrations : ` php bin/console doctrine:migrations:migrate `
5. Lancez le serveur : ` symfony server:start ` ou ` php -S localhost:3000 -t public `
