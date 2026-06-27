ChangeLog
v0.1
- Mise en place du projet Symfony 7.4 webapp
- Ajout ChangeLog

v0.2
- Ajout Entity Character
- Configuration table character
- Creation base de donnees guilde_seigneurs_full
- Configuration .env.local (connexion MySQL)
- Migrations Doctrine
- Ajout doctrine fixtures
- Chargement des characters depuis characters.json
- Ajout CRUD Character (controller, formulaires, templates Twig)
- Ajout tests CharacterController
- Redirection vers show apres creation et edition

v0.3
- Mise en place de Bootstrap

v0.3.1
- Modification de l index
- Configuration DEFAULT_URI pour local.guilde-des-seigneurs-full.com (vhost Laragon)
- trusted_hosts en environnement dev
- Ajout public/.htaccess pour le routage Apache (Laragon vhost)
- Configuration DATABASE_URL MySQL pour l environnement test
- Correction test CharacterController

v0.3.2
- Formatage des Form

v0.4
- Ajout Entity User et relation User/Character
- Registration form avec verification email (verify-email-bundle)
- Configuration mailer (sendmail)
- Login et logout (form_login)

v0.4.1
- Login form et route logout (solution sequence 23)
- Restriction methods sur app_login, app_register, app_verify_email
- Template security/login.html.twig conforme solution

v0.5
- Mise en place de l'utilisation de l'API

v0.5.1
- Vhost front: local.guilde-des-seigneurs.com (remplace local.guilde-des-seigneurs-full.com)
- API_URL: local.api.la-guilde-des-seigneurs.com
- DEFAULT_URI, trusted_hosts et tests alignes sur le nouveau domaine front

v0.5.2
- Front consommateur API: /login, /character, /register (plus de routes /api-*)
- Session JWT (token) pour les appels HTTP vers le backend
- CRUD local deplace sur /character-local
- Inscription via POST /signup du backend

v0.5.3
- Francisation des templates front (liste, fiche, formulaires)
- Boutons Modifier et Supprimer (liste et fiche)
- Fiche personnage: image medias et presentation dynamique en gras
- SessionUserProvider (authentification Symfony / profiler)
- Routes /characters et redirections /character
- Images locales via CHARACTER_MEDIA_BASE (API public/images/images)
- Validation formulaire et gestion erreur 422

v0.5.4
- Normalisation des routes: front API sur /api-character et CRUD local sur /character
- Route explicite /api-character/life/{life} et compatibilite legacy /characters/*
- Suppression du controller life dedie, fusion dans CharacterController
- Retrait de l'authentification Symfony de session (SessionUserProvider et SessionTokenAuthenticator)
- Navbar basee sur le token API en session (Connexion API ou Deconnexion)
- Alignement des routes: CRUD local sur /character et front API sur /api-character
- Ajout du filtre life sur Full et front API avec compatibilite legacy /characters/*

v0.6
- Route Full: /character/life/{level} avec contrainte 1 a 3 chiffres
- Route Front API: /api-character/life/{level} et appel backend /characters/life/{level}
- Repository Full: methode getAllByLifeLevel
- Test Full ajoute sur /character/life/100 et life dans la creation

v0.7
- Ajout des extensions Twig
- FormatIdentifier (filter format_identifier) et GenderSign (function gender)
- FemininExtension (function feminin) et PuissanceExtension (filter puissance)
- Templates api-character/index et show: identifiant formate et signe de genre

v0.8
- Ajout de la Command pour les sitemaps
- Templates Twig sitemaps/sitemap-index.xml.twig et sitemaps/sitemap.xml.twig
- Commande app:create-sitemaps (generation public/sitemap-index.xml et public/sitemap-site.xml)
- Ignore Git des fichiers public/sitemap*

v0.9
- Installation symfony/ux-twig-component
- Composants Twig Button, Alert et CharacterCard (Atomic Design)
- Liste locale /character en grille de cartes avec CharacterCard
- Affichage des flash messages via le composant Alert dans base.html.twig
