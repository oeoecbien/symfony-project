ChangeLog
v0.2
- Ajout Entity Character
- Ajout .htaccess
- Ajout VarDumper (dev)
- Ajout CharacterController
- Ajout des tests

v0.2.1
- Ajout des methods a la route

v0.3
- Ajout de la route create character
- Ajout de la sauvegarde en base de donnees

v0.4
- Ajout du champ identifier non predictible (sha1)
- Ajout de la route GET /characters/{identifier}
- Ajout de la recherche par identifier via service/repository

v0.4.1
- Modifications des donnees dans la base de donnees

v0.5
- Ajout des Voters

v0.6
- Ajout d'une Route index (securisee par voter)
- Ajout de tests negatifs
- Ajout symfony/monolog-bundle
- Contrainte d'identifiant (40 car.) sur PUT/DELETE (coherence avec GET, tests 404)

v0.7
- Ajout de la Route update /characters/{identifier} + PUT

v0.8
- Ajout de la Route delete /characters/{identifier} + DELETE
- Modification des tests pour les rendre dynamiques

v0.8.1
- Ajout des fixtures

v0.8.2
- Ajout du champ modification (date/heure) pour Character
- Migrations pour la base de donnees

v0.9
- Ajout Entity Building + CRUD (/buildings)
- Migrations pour la base de donnees

v0.9.1
- Ajout du champ price et des donnees depuis buildings.json
- Ajout des fixtures de batiments depuis une URL JSON
- Ajout du chateau d'appartenance (building_id) pour Character
- Attribution de Celeborn au Chateau Silken
- Ajout de la commande app:database:refresh (schema, migrations et fixtures)

v0.9.2
- Ajout d'une reponse controlee si les tables character/building sont absentes (HTTP 503 + message)

v0.9.3
- Modification coherente de Character (setter, fixtures, CharacterService)
- Ajout de commentaires de methodes dans BuildingServiceInterface

v0.10
- Ajout symfony/form et symfony/validator
- Ajout de CharacterType et BuildingType (formulaires API, CSRF desactive)
- Creation et mise a jour dans CharacterService et BuildingService via FormFactoryInterface et corps JSON
- Lecture JSON dans CharacterController et BuildingController
- Ajout d'une reponse HTTP 422 en cas d'echec de validation du formulaire
- Modification des tests POST et PUT avec corps JSON

v0.10.1
- Modification de Character pour create/update avec Request::getContent() vers le service
- Ajout de submit(), isEntityFilled() et SluggerInterface dans CharacterService
- Generation du slug depuis le nom dans CharacterService
- Suppression de la gestion locale de InvalidFormException dans CharacterController
- Modification des tests Character POST et PUT (heredoc JSON, PUT partiel)

v0.11
- Modification de Building pour create/update avec Request::getContent() vers le service
- Ajout de submit(), isEntityFilled() et SluggerInterface dans BuildingService
- Generation du slug depuis le nom dans BuildingService
- Suppression de la gestion locale de InvalidFormException dans BuildingController
- Modification des tests Building POST et PUT (heredoc JSON, PUT partiel)

v0.12
- Ajout des contraintes Assert sur Character (NotNull, NotBlank, Length, PositiveOrZero)
- Ajout des contraintes Assert sur Building (NotNull, NotBlank, Length, PositiveOrZero)
- Ajout de la validation automatique via ValidatorInterface dans CharacterService::isEntityFilled()
- Ajout de la validation automatique via ValidatorInterface dans BuildingService::isEntityFilled()

v0.13
- Ajout de l'association Doctrine Building/Character (ManyToOne sur Character, OneToMany sur Building)
- Ajout de findOneByIdentifier() dans BuildingRepository avec leftJoin sur characters
- Modification de BuildingController pour la lecture via MapEntity expr
- Ajout de reponses JSON serialisees dans BuildingController (index, read, create)
- Ajout de serializeJson() dans BuildingService via SerializerInterface
- Ajout de la gestion des references circulaires dans BuildingService
- Ajout symfony/serializer-pack et symfony/expression-language

v0.13.1
- Ajout de findOneByIdentifier() dans CharacterRepository avec leftJoin sur building
- Modification de CharacterController pour la lecture via MapEntity expr
- Ajout de reponses JSON serialisees dans CharacterController (index, read, create)
- Ajout de serializeJson() et de la gestion des references circulaires dans CharacterService
- Suppression de toArray() dans Character et serialisation centralisee dans le service
- Modification des fixtures : Buildings crees avant Characters avec liaison Character/Building

v0.14
- Ajout des classes Event CharacterEvent et BuildingEvent
- Dispatch des events Character created, updated, created.post_database dans CharacterService
- Dispatch des events Building created et created.post_database dans BuildingService
- Ajout des subscribers CharacterListener et BuildingListener (sans mutation des objets)

v0.14.1
- Fix de la serialisation JSON pour ne plus echapper les slashs des chemins image (JSON_UNESCAPED_SLASHES)
- Retour API des chemins image au format /dames/dame.webp (au lieu de \/dames\/dame.webp)

v0.14.2
- Ajout de l'event BuildingEvent::BUILDING_UPDATED
- Dispatch de l'event BUILDING_UPDATED dans BuildingService::update()
- BuildingListener : retrait de 20 points de strength lors d'une modification de Building

v0.15
- Ajout de NelmioApiDocBundle + Twig/Asset pour la documentation OpenAPI
- Ajout des routes de documentation /api/doc et /api/doc.json
- Configuration nelmio_api_doc (infos API, serveurs, zones documentees)
- Documentation OpenAPI des routes Building (index, read, create, update, delete)

v0.15.1
- Decorrelation BDD/API : renommage des colonnes SQL de Character et Building en gls_*
- Conservation du modele API (noms des proprietes inchanges dans le code et les reponses)
- Ajout de la migration Doctrine associee

v0.16
- Ajout de la pagination sur les routes index Character et Building (page/size)
- Ajout de la documentation OpenAPI des parametres de pagination
- Ajout des tests de pagination pour CharacterControllerTest et BuildingControllerTest

v0.16.1
- Ajout du cache HTTP sur les routes GET index/read Character et Building (maxage 3600)
- Activation du http_cache en environnement prod

v0.17
- Ajout des liens HATEOAS (_links) pour Character et Building via les services
- Correction de setLinks() pour gerer les objets pagines (SlidingPagination)
- Ajout de routes images via Finder (/characters/images/{number}, /characters/images/{kind}/{number}, /buildings/images/{number})

v0.18
- Mise en place des routes images et imagesKind
- Character imagesKind: contrainte stricte sur kind (dames|seigneurs|tourmenteurs|tourmenteuses)
- Ajout de CharacterService::getImagesKind() + tests complementaires

v0.19
- Mise en place de la gestion des utilisateurs (entite User, relation Character->User, signin JSON)
- Configuration Security avec provider Doctrine et json_login sur POST /signin
- Ajout des fixtures utilisateurs (hash password, ROLE_ADMIN sur contact@example.com)
- Ajout de UserControllerTest (signin valide et invalide)

v0.19.1
- Correction de la reference circulaire de serialisation sur User (Character<->User)
- Mise a jour du CIRCULAR_REFERENCE_HANDLER dans CharacterService et BuildingService
- Validation via tests CharacterControllerTest et BuildingControllerTest

v1.0
- Mise en place de JWT (UserService, ApiKeyAuthenticator Bearer, protection des routes)
- Reduction des donnees serialisees avec Groups/Ignore sur Character, Building et User
- Adaptation de la documentation API (Bearer JWT) et des tests avec utilisateur authentifie

v1.0.1
- Correction de la documentation

v1.0.2
- Modification coherente de Building (setter modification, fixtures, BuildingService, BuildingType)
- Alignement des longueurs des colonnes gls_name et gls_slug sur Building
- Modification de toArray() sur Building via get_object_vars()
- Simplification des fixtures de batiments (Slugger, chargement JSON)

v1.0.3
- Vhost API: local.api.la-guilde-des-seigneurs.com (DEFAULT_URI dans .env local)
- JWT issuedBy et permittedFor alignes sur le domaine API local

v1.1
- Correction parse JWT signe (plus de filtre UnencryptedToken)
- Cle JWT stable (APP_SECRET), route POST /signup
- Documentation OpenAPI: serveur local.api.la-guilde-des-seigneurs.com

v1.1.1
- Ajout du champ life sur Character (entite, formulaire, fixtures, migration gls_life)
- Ajout du filtrage des personnages par vie minimale (GET /characters/life/{life} et ?life=)
- Mise a jour du service/repository pour la pagination du filtre life

v1.2
- Ajout des extensions Twig
- FormatIdentifier (filter format_identifier) et GenderSign (function gender)
- FemininExtension (function feminin) et PuissanceExtension (filter puissance)
