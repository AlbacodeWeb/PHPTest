PHP Test
==========

Installation du projet
------------------------
Installer composer puis installer les dépendances nécessaires au projet :

`composer install`

Vérifier que tout fonctionne en lançant les tests :

`./vendor/bin/phpunit tests`

Consigne
--------------
Ce projet est une modélisation du modèle familial "traditionnel" :
 - mariage uniquement entre un homme et une femme
 - pas d'enfants avant le mariage
 - possibilité d'avoir un enfant seulement aux couples mixtes et mariés

Hors, la loi et les coutumes ont changé et l'application est devenue obsolète.

Revoyez donc l'application pour prendre en compte ces nouvelles règles sociétales :
 - mariage possible entre deux personnes de même sexe
 - possibilité d'avoir des enfants avant le mariage
 - possibilité à un coupe de femmes d'avoir un enfant
 
Les tests unitaires doivent continuer de fonctionner, afin d'assurer le bon fonctionnement de la famille.

Livrable
--------------
Pour cet exercice, vous devez forker ce projet, puis faire une pull request pour proposer vos modifications.

N'hésitez pas à modifier, refactorer, supprimer, nettoyer le code !

Merci et bon test =)

