podman manifest create gyulek
podman build --platform linux/amd64,linux/arm64  --manifest gyulek -rm .
