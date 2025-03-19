# Projet de Réservation Médicale avec Symfony 7.1 🏥
------------------------------------------------------
📌 Description

Ce projet est un site web permettant aux patients de réserver des consultations médicales en ligne avec différents médecins. 
Il permet également aux médecins de gérer leurs disponibilités et aux administrateurs de superviser les réservations.
---------------------------------------------------------
🚀 Fonctionnalités

🔹 Utilisateurs et Rôles
👤 Patients
✅ Création d’un compte et authentification
✅ Prise de rendez-vous avec un médecin
✅ Consultation et annulation des rendez-vous
✅ Ajout d’avis et de notes sur les médecins

🩺 Médecins
✅ Création d’un compte et authentification
✅ Gestion de leur emploi du temps (disponibilités)
✅ Validation ou refus des rendez-vous

🛠 Administrateurs
✅ Gestion des utilisateurs (patients et médecins)
✅ Gestion des spécialités médicales
✅ Supervision des rendez-vous

--------------------------------------------------------
🏗 Technologies utilisées

Backend : Symfony 7.1 (PHP, Doctrine, Security, Twig)

Base de données : MySQL

Frontend : HTML, CSS (Bootstrap/Tailwind), JavaScript

Outils : Composer
------------------------------------------------------------------
📜 Installation et configuration

composer install
🔹 3️⃣ Configurer la base de données
Dans le fichier .env, modifier la ligne suivante :

env

DATABASE_URL="mysql://root:@127.0.0.1:3306/reservation_app"
Puis exécuter :


php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
🔹 4️⃣ Lancer le serveur

symfony server:start
L'application sera accessible à http://127.0.0.1:8000/.

------------------------------------------------------------
📂 Architecture du projet

/medical-reservation
│── /migrations        → Migrations de la base de données
│── /src
│   ├── Controller     → Logique des pages
│   ├── Entity         → Modèles de la base de données
│   ├── Repository     → Gestion des requêtes en base de données
│── /templates         → Vues avec Twig
│── .env               → Configuration de l’application
│── composer.json      → Dépendances PHP
│── README.md          → Documentation

-----------------------------------------------------------------
💡 Améliorations futures

✅ Paiement en ligne des consultations

✅ Intégration d’une API pour la prise de rendez-vous

✅ Notifications par email/SMS

------------------------------------------------------------------
📬 Contact

📧 Email : alshahoudmohamed95@gmail.com

-----------------------------------------------------------------
🔥 Star ce projet si tu le trouves utile ! ⭐
