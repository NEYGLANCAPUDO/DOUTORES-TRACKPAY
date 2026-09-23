<?php 
session_start();

require_once('MVC/config/auth.php');
require_once('MVC/model/user.php');

if (isset($_SESSION['id'])) {
    
$user = get_user_by_id($_SESSION['id']);}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Organizador Financeiro</title>

    <link rel="stylesheet" href="MVC/view/style.css">

    <style>
        * {
            box-sizing: border-box;
        }

        html,
        body {
            min-height: 100%;
        }

        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background-color: #eef0f2;
            font-family: Arial, sans-serif;
            color: #374151;
        }


        /* =========================
           PARTE SUPERIOR
        ========================= */

        .container {
            width: 90%;
            max-width: 850px;
            margin: 25px auto 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .container h1 {
            font-size: 22px;
            color: #374151;
        }

        .top-bar {
            display: flex;
            align-items: center;
        }

        .btn-new {
            background-color: #2fa86f;
            color: white;
            text-decoration: none;
            padding: 10px 16px;
            border-radius: 5px;
            font-size: 13px;
            font-weight: bold;
        }

        .btn-new:hover {
            background-color: #258b5b;
        }


        /* =========================
           CARD PRINCIPAL
        ========================= */

        .container-inicial {
            width: 90%;
            max-width: 850px;
            margin: 20px auto 25px auto;
            background-color: #f7f7f7;
            border: 1px solid #cfd4d8;
            border-radius: 10px;
            padding: 28px 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        }


        /* =========================
           TÍTULO
        ========================= */

        .container-inicial h2 {
            text-align: center;
            font-size: 23px;
            margin-bottom: 8px;
            color: #374151;
        }

        .descricao {
            text-align: center;
            font-size: 14px;
            color: #606870;
            margin-bottom: 25px;
        }


        /* =========================
           COMO FUNCIONA
        ========================= */

        .titulo-secao {
            font-size: 17px;
            margin-bottom: 15px;
            color: #374151;
        }

        .passos {
            display: flex;
            gap: 12px;
            justify-content: space-between;
            margin-bottom: 25px;
        }

        .passo {
            flex: 1;
            background-color: #ffffff;
            border: 1px solid #d4d8dc;
            border-radius: 7px;
            padding: 15px;
            text-align: center;
        }

        .numero {
            width: 28px;
            height: 28px;
            margin: 0 auto 8px auto;
            background-color: #2fa86f;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 13px;
        }

        .passo h3 {
            font-size: 14px;
            margin-bottom: 6px;
        }

        .passo p {
            font-size: 12px;
            color: #666;
            line-height: 17px;
        }


        /* =========================
           EXEMPLOS
        ========================= */

        .exemplo {
            background-color: #ffffff;
            border: 1px solid #d4d8dc;
            border-radius: 7px;
            padding: 16px;
        }

        .exemplo h3 {
            font-size: 16px;
            margin-bottom: 12px;
            color: #374151;
        }

        .blocos-exemplo {
            display: flex;
            gap: 10px;
        }

        .bloco {
            flex: 1;
            border-left: 4px solid #2fa86f;
            background-color: #f2f3f4;
            padding: 10px;
            border-radius: 4px;
        }

        .bloco h4 {
            font-size: 13px;
            margin-bottom: 5px;
            color: #374151;
        }

        .bloco p {
            font-size: 11px;
            color: #666;
            line-height: 16px;
        }


        /* =========================
           RODAPÉ
        ========================= */

        .rodape {
            margin-top: auto;
            width: 100%;
            text-align: center;
            padding: 10px;
        }

        .rodape .link-politica {
            display: inline-block;
            background-color: #2fa86f;
            color: white !important;
            text-decoration: none !important;
            padding: 6px 10px;
            margin: 2px;
            border-radius: 4px;
            font-size: 9px;
            font-weight: bold;
        }

        .rodape .link-politica:hover {
            background-color: #258b5b;
        }


        /* =========================
           CELULAR
        ========================= */

        @media (max-width: 650px) {

            .container {
                flex-direction: column;
                gap: 15px;
            }

            .passos {
                flex-direction: column;
            }

            .blocos-exemplo {
                flex-direction: column;
            }

        }
    </style>

</head>


<body>
    <?php if (isset($user)): ?>

        <h2>
           <?= $user['nome']; ?>
        </h2> 
    <?php endif; ?>


        <!-- =========================
         TOPO COM LOGIN
    ========================== -->

    <div class="container">

        <h1>Organizador Financeiro</h1>

        <div class="top-bar">

            <a href="MVC/view/login.php" class="btn-new">
                <i class="fa-solid fa-plus"></i> Novo usuário
            </a>

        </div>

    </div>



    <!-- =========================
         INTRODUÇÃO DO SITE
    ========================== -->

    <main class="container-inicial">

        <h2>ORGANIZE SUAS CONTAS</h2>

        <p class="descricao">
            Tenha uma visão simples e organizada das finanças da sua casa.
        </p>


        <h3 class="titulo-secao">
            Como o sistema funciona?
        </h3>


        <div class="passos">


            <!-- PASSO 1 -->

            <div class="passo">

                <div class="numero">1</div>

                <h3>Adicione as rendas</h3>

                <p>
                    Cada pessoa da casa pode informar sua renda.
                    O sistema reúne todos os valores para mostrar
                    a renda total da família.
                </p>

            </div>



            <!-- PASSO 2 -->

            <div class="passo">

                <div class="numero">2</div>

                <h3>Organize as contas</h3>

                <p>
                    Crie blocos para suas despesas, como mensalidades,
                    energia ou internet, e escolha a importância
                    de cada conta.
                </p>

            </div>



            <!-- PASSO 3 -->

            <div class="passo">

                <div class="numero">3</div>

                <h3>Acompanhe os prazos</h3>

                <p>
                    Veja quanto tempo falta para a próxima cobrança
                    e saiba o valor que deverá ser pago.
                </p>

            </div>


        </div>



        <!-- =========================
             EXEMPLOS
        ========================== -->

        <div class="exemplo">

            <h3>Exemplo de organização</h3>


            <div class="blocos-exemplo">


                <!-- EXEMPLO 1 -->

                <div class="bloco">

                    <h4>Mensalidade escolar</h4>

                    <p>Valor: R$ 500,00</p>
                    <p>Importância: 3</p>
                    <p>Próxima cobrança: 5 dias</p>

                </div>



                <!-- EXEMPLO 2 -->

                <div class="bloco">

                    <h4>Conta de energia</h4>

                    <p>Valor: R$ 180,00</p>
                    <p>Importância: 2</p>
                    <p>Próxima cobrança: 10 dias</p>

                </div>



                <!-- EXEMPLO 3 -->

                <div class="bloco">

                    <h4>Internet</h4>

                    <p>Valor: R$ 100,00</p>
                    <p>Importância: 1</p>
                    <p>Próxima cobrança: 15 dias</p>

                </div>


            </div>

        </div>


    </main>



    <!-- =========================
         RODAPÉ
    ========================== -->

    <footer class="rodape">

        <a href="MVC/view/politicadeuso.php" class="link-politica">
            POLÍTICA DE PRIVACIDADE - TERMOS DE USO
        </a>


        <a href="MVC/view/sobrenos.php" class="link-politica">
            SOBRE NÓS
        </a>

    </footer>


</body>

</html>