#!/bin/bash
set -e

# this part is executed only for php container
if [ "$CONTAINER_ROLE" = "php" ]; then
    echo "🎯 Conteneur PHP détecté, exécution de l'entrypoint..."

    echo "⏳ Attente de la disponibilité de MySQL ($DATABASE_HOST)..."
    until mysqladmin ping -h"$DATABASE_HOST" --silent; do
        sleep 2
        echo "🔄 En attente de MySQL..."
    done

    echo "✅ MySQL est disponible !"

    echo "🚀 Exécution des migrations Doctrine..."
    php bin/console doctrine:migrations:migrate --no-interaction
fi

echo "📢 Lancement de $@"
exec "$@"
