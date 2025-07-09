Este projeto consiste em uma aplicação completa, dividida em duas partes principais: uma API RESTful desenvolvida com Laravel e um aplicativo móvel frontend construído com React Native e Expo. O objetivo é fornecer uma solução robusta e escalável para [Inserir breve descrição do propósito do projeto, se conhecido, ou deixar genérico].

Repositórios

•
Frontend (Client): https://github.com/yoshimidevz/expo-hatch

•
API (Backend): https://github.com/yoshimidevz/API-HATCH

Instruções de Execução

Para colocar o projeto em funcionamento, siga os passos abaixo para cada parte da aplicação:

API (Backend) - Laravel

1.
Clone o repositório:

2.
Instale as dependências do Composer:

3.
Configure o arquivo de ambiente:
Copie o arquivo .env.example para .env:

4.
Gere a chave da aplicação:

5.
Execute as migrações do banco de dados:

6.
Inicie o servidor de desenvolvimento:

Frontend (Client) - React Native com Expo

1.
Clone o repositório:

2.
Instale as dependências do npm:

3.
Inicie o aplicativo Expo:

4.
Configure a URL da API:
Certifique-se de que a URL da sua API Laravel esteja configurada corretamente no código do frontend para que ele possa se comunicar com o backend. Geralmente, isso é feito em um arquivo de configuração ou variável de ambiente dentro do projeto React Native. (Ex: services/api.ts ou similar).

Tecnologias Utilizadas

Este projeto utiliza as seguintes tecnologias:

Backend (API)

•
Laravel: Framework PHP para desenvolvimento web.

•
PHP: Linguagem de programação.

Frontend (Client)

•
React Native: Framework para desenvolvimento de aplicativos móveis multiplataforma.

•
Expo: Conjunto de ferramentas e serviços para desenvolvimento de aplicativos React Native.

•
TypeScript: Superset de JavaScript que adiciona tipagem estática.

Organização dos Casos de Uso e Modelos

API (Backend) - Laravel

A estrutura da API Laravel segue as convenções do framework, com a organização de código focada em modularidade e separação de responsabilidades:

•
app/: Contém o código principal da aplicação, incluindo modelos (Models), controladores (Controllers), provedores de serviço (Service Providers) e outras classes de lógica de negócio.

•
Models/: Define a estrutura do banco de dados e as relações entre as tabelas. Cada modelo representa uma entidade no sistema (ex: User, Product, Order).

•
Http/Controllers/: Lida com as requisições HTTP e a lógica de negócio associada a cada rota.



•
database/: Contém as migrações (migrations) para a criação e modificação das tabelhas do banco de dados, e os seeders (seeders) para popular o banco com dados iniciais.

•
routes/: Define as rotas da API, mapeando URLs para os controladores correspondentes.

Frontend (Client) - React Native com Expo

A estrutura do frontend React Native é organizada para facilitar o desenvolvimento e a manutenção, separando os componentes da interface do usuário, a lógica de negócio e a navegação:

•
app/: Contém as telas principais da aplicação, seguindo a abordagem de roteamento baseado em arquivos do Expo.

•
components/: Armazena componentes de UI reutilizáveis, como botões, inputs, cards, etc.

•
contexts/: Gerencia o estado global da aplicação, utilizando a API de Contexto do React para compartilhar dados entre componentes.

•
hooks/: Contém hooks personalizados para encapsular lógica de negócio e reutilizá-la em diferentes componentes.

•
navigation/: Define a estrutura de navegação do aplicativo, incluindo pilhas de navegação e abas.

•
services/: Responsável pela comunicação com a API backend, contendo as configurações do Axios e as funções para realizar requisições HTTP.

•
utils/: Contém funções utilitárias e helpers que podem ser usados em diversas partes da aplicação.

