# Laravel lento? Entenda suas queries

Este repositorio acompanha o video **"Laravel e realmente lento?"** publicado por Lucas Souza (Virgo) no canal Brain Code.
Assista: https://www.youtube.com/watch?v=eAi2lCahmpY

## Ponto principal do video
- Duas queries com o mesmo resultado podem consumir recursos de forma muito diferente.
- Aplicar funcao em cima de coluna indexada (ex.: `DATE(created_at)`) remove a possibilidade de o otimizador usar o indice.
- O impacto aparece quando existem dezenas ou centenas de usuarios concorrendo pelos mesmos dados.
- Antes de culpar o framework, entenda como o banco atende suas queries e quais argumentos sao realmente sargable.

## Queries comparadas na demo
```php
// Rota "bad": quebra o indice ao usar whereDate
Order::query()
    ->whereDate('created_at', $date)
    ->orderByDesc('id')
    ->paginate(50);

// Rota "good": preserva o indice com whereBetween
Order::query()
    ->whereBetween('created_at', [$start, $end])
    ->orderByDesc('id')
    ->paginate(50);
```

A tabela `orders` possui mais de 100 mil linhas e um indice dedicado em `created_at`. Somente a segunda abordagem permite que o MySQL busque usando o indice `idx_created_at` definido em `database/migrations/2025_10_24_140111_create_orders_table.php`.

## Ferramentas mostradas
- `EXPLAIN`: evidencia se a consulta utiliza chave primaria ou indice secundario.
- `mysqlslap`: exercita concorrencias de 1, 10, 40 e 100 usuarios simulando carga real.
- Laravel Octane + FrankenPHP: servidor HTTP que suporta concorrencia alta durante os testes.
- Pest + Stressless: testes automatizados para medir throughput das rotas `/orders/good` e `/orders/bad`.

## Resultados destacados
### mysqlslap
- Query nao sargable (rota "bad"): 32 ms (1 usuario), 600 ms (10), ~4 s (40), ~9 s (100).
- Query sargable (rota "good"): 20 ms (1 usuario), ~100 ms (10), ~0.8 s (40), ~2 s (100).

### Pest Stressless (`tests/Feature/OrdersStressTest.php`)
- Concorrencia 40 durante 10 s: rota "good" respondeu ~1732 requisicoes com media de 256 ms.
- Mesmo cenario na rota "bad": ~80 requisicoes com media de 9 s.

## Como rodar localmente
1. Clonar o repositorio e entrar na pasta do projeto.
2. Copiar o arquivo de ambiente: `cp .env.example .env`.
3. Ajustar as variaveis de banco no `.env`. O `compose.yaml` ja provisiona MySQL via Laravel Sail.
4. Subir os containers: `./vendor/bin/sail up`.
5. Instalar dependencias PHP e JS (caso ainda nao tenha feito): `composer install` e `npm install`.
6. Executar migracoes e seeds (gera 10k usuarios e 500k pedidos, pode levar alguns minutos): `./vendor/bin/sail artisan migrate --seed`.
7. Acessar `http://localhost/orders/good` e `http://localhost/orders/bad` para comparar o tempo de resposta.

## Testes de estresse
- Com o Octane ativo dentro do container Sail, rode `./vendor/bin/sail test --group=comparison` para repetir o comparativo exibido no video.
- Use `./vendor/bin/sail test --group=heavy` para exercitar cenarios com 1000 usuarios simultaneos (demora e consome mais CPU).

## Aprendizados sugeridos
- Pesquise sobre "SARGable arguments" e planeje indices de acordo com os filtros que sua aplicacao usa.
- Monitore queries reais com `EXPLAIN`, `slow query log` ou ferramentas APM antes de trocar de framework.
- Prefira ajustar seus modelos e filtros Eloquent para preservar colunas indexadas em vez de aplicar funcoes no banco.

## Conteudo adicional
Ficou curioso sobre banco de dados no Laravel? 
Leia mais em nosso blog: https://blog.beerandcode.com.br/tutoriais/o-laravel-e-lento-entenda-por-que-sua-aplicacao-nao-escala/
