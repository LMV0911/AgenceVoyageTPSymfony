# 🌍 Agence de Voyage - Symfony 7

Application web complète pour la gestion d’une agence de voyage, développée avec Symfony 7, Twig, Doctrine, Tailwind CSS et Mailer.

---

## 🚀 Fonctionnalités principales

- Gestion des voyages (CRUD, photos, activités associées)
- Gestion des utilisateurs (inscription, connexion, profil, rôles, bannissement)
- Commentaires sur les voyages
- Réinitialisation de mot de passe par email
- Confirmation d’email à l’inscription
- Dashboard administrateur avec statistiques et dernières activités
- Sécurité (authentification, rôles, CSRF)
- Design moderne avec Tailwind CSS

---

## 🛠️ Installation

1. **Clone le dépôt**
git clone <url-du-repo>
cd <nom-du-repo>

text

2. **Installe les dépendances**
composer install
npm install
npm run build # ou npm run dev pour le mode développement

text

3. **Configure l’environnement**
- Copie `.env` en `.env.local` et configure :
  ```
  DATABASE_URL="mysql://user:password@127.0.0.1:3306/nom_bdd"
  MAILER_DSN=smtp://login:password@smtp.mailtrap.io:2525
  ```

4. **Crée la base de données et exécute les migrations**
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate

text

5. **Charge les données de test (fixtures)**
php bin/console doctrine:fixtures:load

text

6. **Lance le serveur Symfony**
symfony server:start

text
ou
php -S localhost:8000 -t public

text

---

## 👤 Accès administrateur

- **Email** : admin@voyage.fr
- **Mot de passe** : password

---

## 📁 Structure des dossiers

src/
├── Controller/
├── Entity/
├── Form/
├── Repository/
templates/
├── admin/
├── voyage/
├── user/
├── ...
public/

text

---

## ✉️ Fonctionnalités email

- Vérification d’adresse à l’inscription
- Réinitialisation de mot de passe
- Utilise [Symfony Mailer](https://symfony.com/doc/current/mailer.html)

---

## 🎨 Frontend

- Twig pour les vues
- Tailwind CSS pour le style (voir `assets/`)

---

## 📝 Contribution

1. Fork le projet
2. Crée une branche (`git checkout -b feature/ma-fonctionnalite`)
3. Commit tes modifications (`git commit -am 'Ajoute une fonctionnalité'`)
4. Push la branche (`git push origin feature/ma-fonctionnalite`)
5. Ouvre une Pull Request

---

## 🛡️ Licence

Ce projet est sous licence MIT.

---

## 💡 Aide

Pour toute question, ouvre une issue ou contacte l’auteur du projet.
