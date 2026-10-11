# IntegraUBS

Sistema para cadastrar e consultar pacientes vinculados a unidades de saúde. Feito como desafio técnico para a vaga de Programador(a) PHP Júnior.

O backend é uma API em Laravel que responde em JSON. O frontend é HTML, CSS e JavaScript puro, que consome essa API com `fetch`.

![Lista de pacientes](public/assets/readme/lista.png)

## Tecnologias

- PHP 8.4 e Laravel 13
- MySQL 8.4
- HTML, CSS e JavaScript puro
- Pest (testes) e Pint (estilo de código)

## Pré-requisitos

- PHP 8.4 ou superior, com as extensões `pdo_mysql`, `mbstring` e `openssl`
- Composer 2
- MySQL 8.4 rodando

## Instalação

1. Clone o repositório e instale as dependências:

```bash
git clone git@github.com:Pedro-HS-Rodrigues/IntegraUBS-Desafio-ConectaSUS.git
cd IntegraUBS-Desafio-ConectaSUS
composer install
```

2. Crie o arquivo `.env` e gere a chave da aplicação:

```bash
cp .env.example .env
php artisan key:generate
```

3. Abra o `.env` e coloque a senha do seu MySQL em `DB_PASSWORD` (e o usuário em `DB_USERNAME`, se não for `root`). O nome do banco é `integraubs`.

4. Crie o banco, as tabelas e os dados de exemplo:

```bash
php artisan migrate:fresh --seed
```

O banco `integraubs` é criado nesse passo, se ainda não existir. O seeder cadastra 4 unidades de saúde de Fortaleza e 20 pacientes de exemplo, entre eles uma "Maria da Silva" para testar a busca.

5. Suba o servidor:

```bash
php artisan serve
```

## Como abrir o frontend

Com o servidor rodando, acesse <http://localhost:8000>. A API fica em <http://localhost:8000/api>.

A documentação da API (Swagger) está em <http://localhost:8000/api/documentation>.

## Endpoints

| Método        | Rota                 | O que faz                                     | Respostas     |
| ------------- | -------------------- | --------------------------------------------- | ------------- |
| `GET`         | `/api/patients`      | Lista pacientes. Aceita `?search=` e `?page=` | 200           |
| `POST`        | `/api/patients`      | Cadastra um paciente                          | 201, 422      |
| `GET`         | `/api/patients/{id}` | Consulta um paciente                          | 200, 404      |
| `PUT`/`PATCH` | `/api/patients/{id}` | Atualiza um paciente                          | 200, 404, 422 |
| `DELETE`      | `/api/patients/{id}` | Exclui um paciente                            | 200, 404      |
| `GET`         | `/api/health-units`  | Lista as unidades de saúde                    | 200           |

Envie sempre o header `Accept: application/json`.

### Exemplo: cadastrar um paciente

- Método e URL: `POST` `http://localhost:8000/api/patients`
- Headers: `Accept: application/json` e `Content-Type: application/json`
- Body (raw, JSON):

```json
{
    "name": "Maria da Silva",
    "cpf": "52998224725",
    "birth_date": "1985-03-10",
    "phone": "85988887777",
    "health_unit_id": 1
}
```

Resposta `201`:

```json
{
    "data": {
        "id": 21,
        "name": "Maria da Silva",
        "cpf": "52998224725",
        "birth_date": "1985-03-10",
        "phone": "85988887777",
        "health_unit_id": 1,
        "health_unit": { "id": 1, "name": "Posto de Saúde Centro" }
    },
    "message": "Paciente cadastrado com sucesso."
}
```

Resposta `422` (erro de validação):

```json
{
    "message": "O campo nome é obrigatório. (e mais 1 erro)",
    "errors": {
        "name": ["O campo nome é obrigatório."],
        "cpf": ["O CPF informado é inválido."]
    }
}
```

## Validações

| Campo            | Regra                                                              |
| ---------------- | ------------------------------------------------------------------ |
| `name`           | obrigatório, até 255 caracteres                                    |
| `cpf`            | obrigatório, só os 11 números (sem pontos e traço), válido e único |
| `birth_date`     | obrigatória e não pode ser uma data futura                         |
| `phone`          | opcional, só números com DDD (10 ou 11 dígitos)                    |
| `health_unit_id` | obrigatório e precisa existir em `health_units`                    |

No formulário, o CPF e o telefone aparecem com máscara, mas são enviados para a API só com os números.

## Banco de dados

- `health_units`: `id`, `name`, `city`, `created_at`, `updated_at`
- `patients`: `id`, `name`, `cpf`, `birth_date`, `phone`, `health_unit_id`, `created_at`, `updated_at`

Uma unidade tem vários pacientes, e cada paciente pertence a uma unidade. O `cpf` é único e o `health_unit_id` é chave estrangeira. As tabelas são criadas pelas migrations.

As consultas pedidas no desafio estão em `sql/consultas.sql`:

1. pacientes com o nome da unidade (`INNER JOIN`);
2. total de pacientes por unidade, inclusive as que não têm paciente (`LEFT JOIN` com `GROUP BY`);
3. pacientes cujo nome contém um termo (`LIKE`).

Para executar no terminal:

```bash
mysql -u root -p integraubs < sql/consultas.sql
```

## Como o código está organizado

- `routes/api.php`: rotas da API.
- `app/Http/Controllers/Api`: recebem a requisição e devolvem a resposta.
- `app/Http/Requests`: validação dos dados (Form Requests).
- `app/Services/PatientService.php`: listagem, cadastro, edição e exclusão.
- `app/Models`: `Patient` e `HealthUnit`, com o relacionamento entre eles.
- `lang/pt_BR`: mensagens em português.
- `public/`: frontend (`index.html`, `css/` e `js/`).

## Testes

```bash
php artisan test
```

São 46 testes. Eles usam SQLite em memória, então não mexem no seu banco.

## O que está funcionando

- Cadastro, listagem, busca por nome, edição e exclusão de pacientes, pela tela e pela API.
- Validações com mensagens em português, mostradas abaixo de cada campo.
- Paginação da lista (10 por página).
- As três consultas de `sql/consultas.sql`.

## O que ficou incompleto

Nenhuma funcionalidade faltante.
