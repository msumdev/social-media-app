VERSION ?= latest
REGISTRY ?= gitlab.restricted.digital:5050/johnj/meetzy

build: docker-build docker-push
build-environment: build-create build-push

docker-build:
	docker buildx build --progress=plain . --target meetzy_backend -t ${REGISTRY}/meetzy_backend:${VERSION}
	docker buildx build --progress=plain . --target meetzy_web_server -t ${REGISTRY}/meetzy_web_server:${VERSION}

docker-push:
	docker push ${REGISTRY}/meetzy_backend:${VERSION}
	docker push ${REGISTRY}/meetzy_web_server:${VERSION}
