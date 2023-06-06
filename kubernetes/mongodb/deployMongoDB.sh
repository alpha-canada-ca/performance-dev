helm install -f values.yml mongodb-dev bitnami/mongodb --set auth.enabled=false --namespace performance
