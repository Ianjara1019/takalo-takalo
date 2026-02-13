# TODO Projet Takalo-Takalo

Mise a jour: 11/02/2026

Objectif: chaque ligne de tache decrit explicitement ce qui est deja fait dans le code.

## Module: Inscription

| Module | Tache | Estimation (min) | Reste a faire (min) | Avancement (%) |
|---|---|---:|---:|---:|
| Inscription | Page inscription deja creee (`GET /inscription` + vue `app/views/user/inscription.php`) | 30 | 0 | 100 |
| Inscription | Insertion utilisateur deja implementee (`Utilisateur::inscrire` + hash mot de passe + controle email existant) | 45 | 0 | 100 |
| Inscription | Soumission AJAX implementee (`fetch` + retour JSON + redirection front) | 30 | 0 | 100 |
| Inscription | Validation serveur des champs obligatoires implementee (nom, prenom, email, password, telephone) via `FormValidator` | 35 | 0 | 100 |
| Inscription | Regle mot de passe fort implementee (8+ chars, majuscule, minuscule, chiffre, caractere special) | 25 | 0 | 100 |

## Module: Connexion utilisateur

| Module | Tache | Estimation (min) | Reste a faire (min) | Avancement (%) |
|---|---|---:|---:|---:|
| Connexion utilisateur | Page login deja creee (`GET /login` + vue `app/views/user/login.php`) | 25 | 0 | 100 |
| Connexion utilisateur | Verification identifiants deja implementee (`Utilisateur::login` + `password_verify`) | 35 | 0 | 100 |
| Connexion utilisateur | Variables de session utilisateur deja stockees apres login | 20 | 0 | 100 |
| Connexion utilisateur | Soumission AJAX implementee (`fetch` + retour JSON sur succes/erreur) | 25 | 0 | 100 |
| Connexion utilisateur | Detection requete AJAX factorisee dans `RequestHelper::isAjaxRequest` | 15 | 0 | 100 |
| Connexion utilisateur | Regeneration ID session au login pas encore faite (protection fixation session) | 20 | 20 | 0 |
| Connexion utilisateur | Logout existe deja (`session_destroy`) mais nettoyage fin de session a renforcer | 20 | 10 | 50 |

## Module: Objets (liste/recherche/fiche)

| Module | Tache | Estimation (min) | Reste a faire (min) | Avancement (%) |
|---|---|---:|---:|---:|
| Objets | Liste objets disponibles deja affichee (`GET /objets` + `Objet::getAutresObjets`) | 40 | 5 | 88 |
| Objets | Recherche deja active (mot-cle + categorie via `Objet::search`) | 40 | 10 | 75 |
| Objets | Fiche objet deja complete (infos objet, photos, historique, formulaire proposition) | 45 | 10 | 78 |
| Objets | Gestion explicite du cas objet inexistant a ajouter (actuellement risque acces tableau null) | 25 | 25 | 0 |

## Module: Mes objets (CRUD)

| Module | Tache | Estimation (min) | Reste a faire (min) | Avancement (%) |
|---|---|---:|---:|---:|
| Mes objets | Creation objet deja faite (`POST /objet/ajouter` + insertion DB + validation serveur) | 45 | 0 | 100 |
| Mes objets | Upload multiple deja code (`move_uploaded_file` + photo principale) | 50 | 25 | 50 |
| Mes objets | Modification objet deja fonctionnelle (`POST /objet/modifier/@id` + validation serveur) | 40 | 0 | 100 |
| Mes objets | Soumission AJAX implementee pour ajout/modification (modales + `fetch` + JSON) | 35 | 0 | 100 |
| Mes objets | Validation centralisee avec `FormValidator::validateObjetData` | 20 | 0 | 100 |
| Mes objets | Suppression objet deja presente mais via route GET (a migrer en POST) | 30 | 20 | 33 |
| Mes objets | Controle proprietaire deja implemente avant modifier/supprimer | 20 | 0 | 100 |

## Module: Propositions et echanges

| Module | Tache | Estimation (min) | Reste a faire (min) | Avancement (%) |
|---|---|---:|---:|---:|
| Propositions et echanges | Creation proposition deja en place (`POST /proposition/creer` + `Proposition::create` + validation serveur) | 40 | 0 | 100 |
| Propositions et echanges | Soumission AJAX implementee pour formulaire proposition (`fetch` + JSON) | 25 | 0 | 100 |
| Propositions et echanges | Validation centralisee avec `FormValidator::validatePropositionData` | 20 | 0 | 100 |
| Propositions et echanges | Pages recues/envoyees deja en place (`GET /echanges` + vue onglets) | 35 | 5 | 86 |
| Propositions et echanges | Acceptation deja operationnelle (transaction + creation echange + transfert proprietaires) | 50 | 15 | 70 |
| Propositions et echanges | Refus deja operationnel (`UPDATE statut = refuse`) | 25 | 5 | 80 |
| Propositions et echanges | Acceptation/refus encore exposes en GET (a convertir en POST) | 30 | 30 | 0 |
| Propositions et echanges | Regles metier partiellement implementees: auto-proposition et objets indisponibles bloques, doublons a faire | 40 | 15 | 63 |

## Module: Administration

| Module | Tache | Estimation (min) | Reste a faire (min) | Avancement (%) |
|---|---|---:|---:|---:|
| Administration | Login admin deja en place (`GET/POST /admin/login` + `Admin::login`) | 30 | 5 | 83 |
| Administration | Dashboard deja branche (compteurs utilisateurs/objets/echanges/propositions/categories) | 45 | 10 | 78 |
| Administration | Gestion categories deja fonctionnelle (liste/ajout/modif/suppression) | 50 | 15 | 70 |
| Administration | Suppression categorie encore en GET (a migrer en POST + CSRF) | 25 | 25 | 0 |

## Module: Securite

| Module | Tache | Estimation (min) | Reste a faire (min) | Avancement (%) |
|---|---|---:|---:|---:|
| Securite | Echappement HTML deja present dans la plupart des vues (`htmlspecialchars`) | 45 | 10 | 78 |
| Securite | Protection CSRF non implementee sur formulaires/actions sensibles | 90 | 90 | 0 |
| Securite | Validation stricte entree serveur renforcee (inscription/login/objets/propositions) via `FormValidator`, encore incomplete globalement | 60 | 15 | 75 |
| Securite | Durcissement upload image incomplet (MIME/taille/extensions autorisees) | 75 | 60 | 20 |

## Module: Tests et documentation

| Module | Tache | Estimation (min) | Reste a faire (min) | Avancement (%) |
|---|---|---:|---:|---:|
| Tests et documentation | README et QUICKSTART deja presentes avec installation + comptes de test | 45 | 10 | 78 |
| Tests et documentation | Checklist de tests manuels encore a formaliser dans un fichier dedie | 45 | 30 | 33 |
| Tests et documentation | Tests automatises non presents (integration/validation des flows critiques) | 120 | 120 | 0 |
