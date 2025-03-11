# Test NSN Microservice Symfony

Voici le deuxième test technique sur lequel il fallait développer un microservice avec Symfony.

Je suis parti de votre idée de taper des API des marchés financiés.

Je l'ai orienté cryptomonnaie.

L'idée de l'app est simple, aggrégé via plusieurs providers la data et afficher la moyenne des prix de ces différents providers.

Exemple, je veux connaitre le prix moyen du BTC en USD basé sur plusieurs providers : 

```
{
  "ticker": "BTCUSD",
  "average_price": 81473.3
}
```

# Les providers utilisés

Ici on utilise Coinmarketcap et Coingecko qui offrent tout deux une API robuste.

Mais j'ai pensé le code pour facilement en ajouter à l'avenir. (voir src/ApiProvider)

# Lancer le projet

En premier, il faut git clone le projet.

Ensuite, il faut créer le fichier .env.local et dedans mettre vos clé API : 

```
###> API CONFIGURATION ###
COINMARKETCAP_API_KEY="CLE_API_CCM"

COINGECKO_API_KEY="CLE_API_CG"
###< API CONFIGURATION ###
```

Je vous ai envoyé mes clés api par mail pour le projet.

Ensuite, il suffit de lancer : 

```
docker compose build 
docker compose up -d
```

# Récuperer la data : 

Soit vous attendez 5 minutes (que la crontab passe pour récupérer les data)

Soit vous éxectuer la commande Symfony `php bin/console app:fetch-all-crypto-prices
` dans le bash container php.

# Lire la data : 

Rendez-vous ici : `http://localhost:8080/api` pour parcourir le swagger de ApiPlatform (bien attendre que le container PHP a fini d'installer tout le nécessaire. voir entrypoint.sh pour plus d'infos)

# Lancer les tests unitaires : 

Dans le bash de symfony-php, lancer `php vendor/bin/phpunit`  
