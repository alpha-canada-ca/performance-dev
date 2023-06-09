#!/bin/sh
export GPG_TTY=$(tty)
mkdir ./docker/performance-dev_php/secrets/
mkdir ./docker/performance-dev_php/secrets/keys/
mkdir ./docker/performance-dev_php/secrets/php/

gpg --quiet --batch --yes --decrypt --passphrase="$SECRETS_PASSPHRASE" \
--output ./docker/performance-dev_php/secrets/keys/certificate.pem ./docker/performance-dev_php/secrets_enc/keys/certificate.pem.gpg

gpg --quiet --batch --yes --decrypt --passphrase="$SECRETS_PASSPHRASE" \
--output ./docker/performance-dev_php/secrets/keys/secret.key ./docker/performance-dev_php/secrets_enc/keys/secret.key.gpg

gpg --quiet --batch --yes --decrypt --passphrase="$SECRETS_PASSPHRASE" \
--output ./docker/performance-dev_php/secrets/keys/secret.pem ./docker/performance-dev_php/secrets_enc/keys/secret.pem.gpg

gpg --quiet --batch --yes --decrypt --passphrase="$SECRETS_PASSPHRASE" \
--output ./docker/performance-dev_php/secrets/php/config.php ./docker/performance-dev_php/secrets_enc/php/config.php.gpg

gpg --quiet --batch --yes --decrypt --passphrase="$SECRETS_PASSPHRASE" \
--output ./docker/performance-dev_php/secrets/php/config-at.php ./docker/performance-dev_php/secrets_enc/php/config-at.php.gpg

gpg --quiet --batch --yes --decrypt --passphrase="$SECRETS_PASSPHRASE" \
--output ./docker/performance-dev_php/secrets/php/service-account-credentials.json ./docker/performance-dev_php/secrets_enc/php/service-account-credentials.json.gpg

ls -la ./docker/performance-dev_php/secrets/keys
ls -la ./docker/performance-dev_php/secrets/php



