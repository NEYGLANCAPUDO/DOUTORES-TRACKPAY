<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Sobre Nós</title>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            background-color: #f4f5f6;
            color: #222;
        }

        .container {
            width: 90%;
            max-width: 900px;
            margin: 30px auto;
        }

        .sobre {
            background-color: #fff;
            border: 1px solid #d5dadd;
            border-radius: 8px;
            padding: 35px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        }

        h1 {
            text-align: center;
            font-size: 26px;
            margin-bottom: 30px;
            color: #222;
        }

        h2 {
            font-size: 18px;
            margin-top: 25px;
            margin-bottom: 10px;
            color: #222;
        }

        p {
            font-size: 14px;
            line-height: 1.6;
            margin-bottom: 12px;
            color: #444;
        }

        .destaque {
            background-color: #f4f5f6;
            border-left: 4px solid #2ac769;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }

        .equipe {
            margin-top: 20px;
            padding: 15px;
            background-color: #fafafa;
            border: 1px solid #e0e0e0;
            border-radius: 5px;
        }

        .equipe p {
            margin-bottom: 6px;
        }

        .botoes {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 30px;
        }

        .botao {
            padding: 10px 22px;
            border: none;
            border-radius: 4px;
            font-size: 14px;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
        }

        .voltar {
            background-color: #899497;
            color: white;
        }

        .voltar:hover {
            background-color: #737f82;
        }

        .rodape {
            text-align: center;
            margin-top: 35px;
            font-size: 11px;
            color: #777;
        }

        @media (max-width: 600px) {
            .container {
                width: 95%;
            }

            .sobre {
                padding: 25px 20px;
            }

            h1 {
                font-size: 23px;
            }

            .botoes {
                justify-content: center;
            }
        }
    </style>
</head>

<body>

    <div class="container">

        <div class="sobre">

            <h1>SOBRE NÓS</h1>

            <h2>Quem somos</h2>

            <p>
                Somos uma equipe de estudantes composta por Aisha, Mateus, Felipe Az, Filipe Oli, Neyglan, que desenvolveram esta
                plataforma com o objetivo de facilitar a organização e o
                acompanhamento das contas domésticas.
            </p>

            <p>
                O sistema foi criado pensando na necessidade de controlar
                despesas, acompanhar pagamentos e manter as informações
                financeiras organizadas de maneira simples e prática.
            </p>

            <div class="destaque">
                <p>
                    <strong>Nosso objetivo:</strong> ajudar o usuário a
                    acompanhar suas contas domésticas de forma mais
                    organizada, facilitando o controle das despesas e dos
                    pagamentos.
                </p>
            </div>

            <h2>O que o sistema oferece</h2>

            <p>
                A plataforma permite cadastrar e acompanhar contas
                domésticas, visualizar valores, datas de vencimento e
                situações de pagamento.
            </p>

            <p>
                Dessa forma, o usuário pode ter uma visão mais clara de suas
                despesas e evitar que informações importantes sobre suas
                contas sejam esquecidas.
            </p>

            <h2>Por que criamos este projeto?</h2>

            <p>
                Este projeto foi desenvolvido como uma forma de aplicar na
                prática os conhecimentos adquiridos durante o curso de
                Informática, utilizando programação, desenvolvimento web e
                organização de dados.
            </p>

            <p>
                Além do aprendizado, buscamos criar uma ferramenta simples,
                funcional e fácil de utilizar no dia a dia.
            </p>

            <h2>Nossa equipe</h2>

            <div class="equipe">
                <p><strong>Projeto:</strong> Sistema de Tracking de Contas Domésticas</p>
                <p><strong>Área:</strong> Informática</p>
                <p><strong>Turma:</strong> Informática 2º Ano</p>
            </div>

            <div class="botoes">
                <a href="../../index.php" class="botao voltar">Voltar</a>
            </div>

            <div class="rodape">
                INFORMÁTICA 2 ANO
            </div>

        </div>

    </div>

</body>
</html>