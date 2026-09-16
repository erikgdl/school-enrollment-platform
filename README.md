# Plataforma de Matrículas Escolares

API desenvolvida em Laravel para gerenciar alunos, cursos e matrículas em uma plataforma escolar.

O projeto tem como objetivo praticar conceitos importantes do Laravel, como Models, Migrations, Controllers, Form Requests, Actions, Events, Listeners, Mail, validações, relacionamentos e regras de negócio.

---

## Sobre o projeto

A aplicação permite cadastrar alunos, cadastrar cursos e processar matrículas.

A matrícula só pode ser aprovada se o aluno não possuir pendência financeira, se o curso tiver vagas disponíveis e se o aluno ainda não estiver matriculado no mesmo curso.

Quando uma matrícula é aprovada, o sistema dispara um evento e gera um comprovante de matrícula por e-mail.

---

## Tecnologias utilizadas

- PHP
- Laravel
- MySQL
- DBeaver
- Postman
- Composer

---

## Estrutura principal

```txt
app/
├── Actions/
│   └── ProcessarMatriculaAction.php
├── Events/
│   └── MatriculaAprovada.php
├── Http/
│   ├── Controllers/
│   │   ├── AlunoController.php
│   │   ├── CursoController.php
│   │   └── MatriculaController.php
│   └── Requests/
│       ├── AlunoRequest.php
│       ├── CursoRequest.php
│       └── MatriculaRequest.php
├── Listeners/
│   └── EnviarComprovantePorEmail.php
├── Mail/
│   └── ComprovanteMatriculaMail.php
└── Models/
    ├── Aluno.php
    ├── Curso.php
    └── Matricula.php
```

---

## Entidades do sistema

### Aluno

Representa um aluno cadastrado na plataforma.

Campos principais:

```txt
id
nome
email
cpf
possui_pendencia
created_at
updated_at
```

### Curso

Representa um curso disponível para matrícula.

Campos principais:

```txt
id
nome
descricao
vagas
vagas_disponiveis
valor
created_at
updated_at
```

### Matrícula

Representa a matrícula de um aluno em um curso.

Campos principais:

```txt
id
aluno_id
curso_id
status
data_matricula
created_at
updated_at
```

---

## Relacionamentos

Um aluno pode ter várias matrículas.

```php
public function matriculas()
{
    return $this->hasMany(Matricula::class);
}
```

Um curso pode ter várias matrículas.

```php
public function matriculas()
{
    return $this->hasMany(Matricula::class);
}
```

Uma matrícula pertence a um aluno.

```php
public function aluno()
{
    return $this->belongsTo(Aluno::class);
}
```

Uma matrícula pertence a um curso.

```php
public function curso()
{
    return $this->belongsTo(Curso::class);
}
```

---

## Regras de negócio

A regra principal do sistema está na `ProcessarMatriculaAction`.

Para uma matrícula ser aprovada:

1. O aluno precisa existir.
2. O curso precisa existir.
3. O aluno não pode possuir pendência financeira.
4. O curso precisa ter vagas disponíveis.
5. O aluno não pode estar matriculado no mesmo curso duas vezes.
6. Ao aprovar a matrícula, uma vaga disponível do curso é reduzida.
7. Após a aprovação, o evento `MatriculaAprovada` é disparado.
8. O listener `EnviarComprovantePorEmail` gera o comprovante da matrícula.

---

## Fluxo da matrícula

```txt
Requisição POST /api/matriculas
↓
MatriculaRequest valida aluno_id e curso_id
↓
MatriculaController recebe a requisição
↓
Controller busca Aluno e Curso
↓
ProcessarMatriculaAction executa as regras de negócio
↓
Matrícula é criada
↓
Vaga disponível do curso é reduzida
↓
Evento MatriculaAprovada é disparado
↓
Listener EnviarComprovantePorEmail é executado
↓
ComprovanteMatriculaMail gera o e-mail
```

---

## Endpoints da API

### Alunos

#### Listar alunos

```http
GET /api/alunos
```

#### Criar aluno

```http
POST /api/alunos
```

Exemplo de body:

```json
{
  "nome": "Erik Gabriel",
  "email": "erik@email.com",
  "cpf": "12345678900",
  "possui_pendencia": false
}
```

#### Buscar aluno por ID

```http
GET /api/alunos/{aluno}
```

#### Atualizar aluno

```http
PUT /api/alunos/{aluno}
```

#### Deletar aluno

```http
DELETE /api/alunos/{aluno}
```

---

### Cursos

#### Listar cursos

```http
GET /api/cursos
```

#### Criar curso

```http
POST /api/cursos
```

Exemplo de body:

```json
{
  "nome": "Laravel do Zero",
  "descricao": "Curso básico de Laravel",
  "vagas": 10,
  "valor": 199.90
}
```

Ao criar um curso, o campo `vagas_disponiveis` recebe o mesmo valor de `vagas`.

#### Buscar curso por ID

```http
GET /api/cursos/{curso}
```

#### Atualizar curso

```http
PUT /api/cursos/{curso}
```

#### Deletar curso

```http
DELETE /api/cursos/{curso}
```

---

### Matrículas

#### Listar matrículas

```http
GET /api/matriculas
```

#### Criar matrícula

```http
POST /api/matriculas
```

Exemplo de body:

```json
{
  "aluno_id": 1,
  "curso_id": 1
}
```

#### Buscar matrícula por ID

```http
GET /api/matriculas/{matricula}
```

#### Cancelar matrícula

```http
POST /api/matriculas/{matricula}/cancelar
```

---

## Validações

O projeto utiliza Form Requests para validar os dados antes de chegar no Controller.

Requests criados:

```txt
AlunoRequest
CursoRequest
MatriculaRequest
```

Exemplo de validação da matrícula:

```php
public function rules(): array
{
    return [
        'aluno_id' => ['required', 'exists:alunos,id'],
        'curso_id' => ['required', 'exists:cursos,id'],
    ];
}
```

Se um aluno ou curso inexistente for enviado, o Laravel retorna erro `422`.

Exemplo:

```json
{
  "message": "O aluno informado não existe.",
  "errors": {
    "aluno_id": [
      "O aluno informado não existe."
    ]
  }
}
```

---

## Action principal

A `ProcessarMatriculaAction` concentra a regra de negócio da matrícula.

Ela verifica se o aluno possui pendência, se o curso possui vaga e se o aluno já está matriculado naquele curso.

Exemplo da lógica principal:

```php
if ($aluno->possui_pendencia) {
    throw new \Exception('Aluno possui pendência financeira.');
}

if ($curso->vagas_disponiveis <= 0) {
    throw new \Exception('Curso não possui vagas disponíveis.');
}

$jaMatriculado = Matricula::where('aluno_id', $aluno->id)
    ->where('curso_id', $curso->id)
    ->where('status', 'aprovada')
    ->exists();

if ($jaMatriculado) {
    throw new \Exception('Aluno já está matriculado nesse curso.');
}
```

A Action também utiliza transação com banco de dados:

```php
DB::transaction(function () {
    // regras de negócio
});
```

Isso garante que, se algo der errado durante o processo, o banco desfaz as alterações.

---

## Event, Listener e Mail

Quando uma matrícula é aprovada, o sistema dispara o evento:

```php
event(new MatriculaAprovada($matricula));
```

O listener `EnviarComprovantePorEmail` escuta esse evento e envia o e-mail usando a classe:

```txt
ComprovanteMatriculaMail
```

Durante o desenvolvimento, o envio de e-mail foi configurado para cair no log.

No arquivo `.env`:

```env
MAIL_MAILER=log
```

Assim, o e-mail gerado pode ser visto em:

```txt
storage/logs/laravel.log
```

---

## Como rodar o projeto

Clone o repositório:

```bash
git clone URL_DO_REPOSITORIO
```

Entre na pasta:

```bash
cd school-enrollment-platform
```

Instale as dependências:

```bash
composer install
```

Copie o arquivo de ambiente:

```bash
cp .env.example .env
```

Gere a chave da aplicação:

```bash
php artisan key:generate
```

Configure o banco no `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=school_enrollment_platform
DB_USERNAME=root
DB_PASSWORD=
```

Rode as migrations:

```bash
php artisan migrate
```

Inicie o servidor:

```bash
php artisan serve
```

A API ficará disponível em:

```txt
http://127.0.0.1:8000/api
```

---

## Resetar banco em ambiente de estudo

Para apagar todas as tabelas e recriar o banco:

```bash
php artisan migrate:fresh
```

Para recriar o banco e rodar seeders:

```bash
php artisan migrate:fresh --seed
```

---

## Testes dos Endpoints

### Criar aluno

```http
POST http://127.0.0.1:8000/api/alunos
```

Body:

```json
{
  "nome": "Erik Gabriel",
  "email": "erik@email.com",
  "cpf": "12345678900",
  "possui_pendencia": false
}
```

### Criar curso

```http
POST http://127.0.0.1:8000/api/cursos
```

Body:

```json
{
  "nome": "Laravel do Zero",
  "descricao": "Curso básico de Laravel",
  "vagas": 10,
  "valor": 199.90
}
```

### Criar matrícula

```http
POST http://127.0.0.1:8000/api/matriculas
```

Body:

```json
{
  "aluno_id": 1,
  "curso_id": 1
}
```

Resultado esperado:

```json
{
  "aluno_id": 1,
  "curso_id": 1,
  "status": "aprovada"
}
```

Depois da matrícula, o curso deve ter uma vaga disponível a menos.

---

## Conceitos praticados

Neste projeto foram praticados:

- Criação de Models
- Criação de Migrations
- Relacionamentos Eloquent
- Controllers
- Rotas de API
- Form Requests
- Validação de dados
- Actions
- Regras de negócio
- Transações com banco de dados
- Events
- Listeners
- Mail
- Testes manuais com Postman
- Integração com MySQL
- Uso do DBeaver

---

## Status do projeto

Projeto finalizado com API funcional para gerenciamento de alunos, cursos e matrículas.

Funcionalidades principais concluídas:

```txt
✅ Cadastro de alunos
✅ Cadastro de cursos
✅ Processamento de matrículas
✅ Validações com Form Request
✅ Regras de negócio em Action
✅ Transação no banco de dados
✅ Evento de matrícula aprovada
✅ Listener para envio de comprovante
✅ Mail registrado no log
✅ Testes manuais no Postman
```
