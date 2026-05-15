# TD3 BACKEND (Locadora de Carros)

## Sobre o projeto

Pequeno projeto de um CRUD feito em PHP puro para a disciplina de Desenvolvimento de Web Back-end da universidade UNIFAP. Nosso projeto tem como objetivo, criar um sistema de locação de diversos veículos

### Equipe do projeto

- Arthur de Araujo Neves
- Natanael Marcelino
- Gabriel Rodrigues
- Pedro Emanuel

## Pré-requisitos

1. [Docker](https://www.docker.com)
2. Gerenciador de bancos de dados (Dbeaver, HeidiSQL, BeeKeeper etc...)

## Configurando projeto

Clone o repositório em sua máquina
```shell
git clone https://github.com/ArthurDeAraujoNeves3/VoltLocadora.git # Windows
git clone git@github.com:ArthurDeAraujoNeves3/VoltLocadora.git # Linux
```

Crie o arquivo `.env` com base no `.env.example`
```shell
cp .env.example .env
```
Após isso, preencha os campos com os devidos valores

Para melhor organização em nossas branchs, utilize o **git flow** para isso. Talvez seja necessário instalar ela em sua máquina
```shell
git flow init
```

Suba os contêiners necessários para rodar o projeto
```shell
docker compose up -d
```
> [!NOTE]
> Acessando o arquivo `docker-compose.yml`, você irá ver que temos dois serviços, sendo o primeiro o **www** (Apache), ele vai permitir que possamos acessar nosso projeto no navegador. Como segundo e último serviço temos o **mysql**, que será o banco de dados utilizado no nosso projeto. Dentro desse arquivo também definimos as portas onde cada processo irá rodar, sendo o apache (80) e Mysql(3007).

Para ver o projeto, basta acessar a url [http://localhost:80](http://localhost:80)
