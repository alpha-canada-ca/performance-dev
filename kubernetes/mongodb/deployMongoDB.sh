helm install -f values.yml mongodb bitnami/mongodb --set auth.enabled=false --namespace performance
