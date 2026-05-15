# TD3 BACKEND (Locadora de Carros)

## Breve explicação

Pequeno projeto de um CRUD feito em PHP puro para a disciplina de Desenvolvimento de Web Back-end da universidade UNIFAP. Nosso projeto tem como objetivo, criar um sistema de locação de diversos veículos

### Equipe do projeto

- Arthur de Araujo Neves
- Natanael Marcelino
- Gabriel Rodrigues
- Pedro Emanuel

## Pré-requisitos

1. [Docker](https://www.docker.com)
2. Gerenciador bancos de dados (Dbeaver, HeidiSQL, BeeKeeper etc...)

## Configurando projeto

Clone o repositório em sua máquina
```shell
git clone url
```

Crie o arquivo .env com base no .env.example
```shell
cp .env.example .env
```
Após isso, preencha os campos com os devidos valores

Suba contêiners necessários para rodar o projeto
```shell
docker compose up -d
```
Acessando o arquivo `docker-compose.yml`, você irá ver que temos dois serviços, sendo o primeiro o **www** (Apache), ele vai permitir que possamos acessar nosso projeto no navegador. Como segundo e último serviço temos o **Mysql**, que será o banco de dados utilizado no nosso projeto. Dentro desse arquivo também definimos as portas onde cada processo irá rodar, sendo o apache (80) e Mysql(3007).

Agora, para acessar o projeto, basta digitar no seu navegador [http://localhost:80](http://localhost:80)
