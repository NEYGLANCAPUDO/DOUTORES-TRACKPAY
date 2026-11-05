<?php session_start(); ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Usuário</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="../../assets/css/cadastrar.css">
</head>
<body>
    <div class="container">
        <h1>Cadastrar Usuário</h1>
        <form method="POST" action="../controller/usuarioController.php?acao=cadastrar" novalidate>
            <div class="form-group">
                <label for="name">Nome Completo</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Senha</label>
                <input type="password" id="password" name="password" placeholder="********" minlength="8" required>
            </div>
            <div class="form-group">
                <label for="pass_verify">Confirme a senha</label>
                <input type="password" id="pass_verify" name="pass_verify" placeholder="********" minlength="8" required>
            </div>
            <?php if (isset($_SESSION['msg'])): ?>
                <p><?= htmlspecialchars($_SESSION['msg'], ENT_QUOTES, 'UTF-8'); ?></p>
                <?php unset($_SESSION['msg']); ?>
            <?php endif; ?>
            <div class="buttons">
                <a href="../../index.php" class="btn btn-cancel">Cancelar</a>
                <button type="submit" class="btn btn-save">Salvar</button>
            </div>
        </form>
        <div class="footer">INFORMATICA 2 ANO</div>
    </div>
</body>
</html>
