# Hash Backend Challenge
Teste para vaga de desenvolvedor backend da Hash

## Stack utilizada
- PHP 8.0.2
- Framework Lumen 8
- Docker
- Docker Compose

## Executando a aplicação
Copiar o conteúdo do arquivo **.env.example** para um novo arquivo **.env**.

Executar o seguinte comando na raiz do projeto:
```
$ docker-compose up -d
```

A instalação das dependencias do projeto é feita assim que o docker é iniciado. Portanto, não há necessidade de realizar este procedimento manualmente.

## Executando os testes unitários


## Interagindo com o composer
Para executar qualquer ação no composer do PHP utilizar o seguinte comando:
```
$ docker run --rm --interactive --tty \
  --volume $PWD:/app \
  composer <command>
```

## Atualizando os clients gRPC
Caso seja criado ou alterado algum proto file, é possível atualizá-los utilizando o seguinte comando:
```
$ docker run -v `pwd`:/defs namely/protoc-all -f discount.proto -l php -o app/Proto/
```
<small>No exemplo acima, estão sendo geradas as classes para o arquivo `discount.proto`</smal>.
