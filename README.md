# TrackPay mesclado

Este projeto junta arquivos dos dois ZIPs enviados, sem adicionar um sistema financeiro novo:

- A página de contas (cartões, formulário em janela/modal, campo de categoria, valor, vencimento, descrição e importância) veio de `TrackPay - site foda`.
- Cadastro, login, gerenciamento do usuário e opção de apagar contas foram mantidos a partir de `DOUTORES-TRACKPAY` e conectados à tela de contas.
- CSS e Font Awesome do projeto visual foram copiados para `assets/`. A área de gerenciamento do usuário continua usando os arquivos próprios do projeto-base.
- O banco foi unificado em um único esquema. Como o banco do projeto visual usa `user.id`, `bill.id`, `bill.user_id`, `bill.name` e `bill.price`, o código do projeto-base foi adaptado para esses nomes, evitando manter duas colunas duplicadas para a mesma informação.

## Executar no XAMPP

1. Extraia a pasta `TrackPay-Mesclado` para `C:\xampp\htdocs\TrackPay-Mesclado`.
2. Inicie Apache e MySQL.
3. Acesse `http://localhost/TrackPay-Mesclado/`.
4. Faça login ou crie uma conta.

A conexão está configurada para `localhost`, usuário MySQL `root`, senha vazia e banco `trackpay`, como nos projetos originais.

## Banco de dados existente

O banco `trackpay` que usa as colunas `user.id` e `bill.user_id` é o formato esperado por esta versão. **Não importe `database/schema.sql` por cima de um banco existente**, porque ele serve para uma instalação nova. Faça backup antes de qualquer alteração no banco.

O arquivo `database/schema.sql` é o esquema unificado para uma instalação nova; ele reúne as tabelas existentes dos dois esquemas em uma estrutura única, usando os nomes do banco visual para usuários, contas e categorias.

## Observações

- O painel visual mantém os espaços de demonstração “Gráfico aqui” e “Linha do tempo” que já existiam no outro projeto; não foram criados gráficos novos.
- A criação da conta usa os campos e a validação do formulário original do projeto visual.
- Não foi feito teste de ponta a ponta com o MySQL deste ambiente. O código deve ser testado no XAMPP usando uma cópia do seu banco.
