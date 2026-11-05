<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LOGIN</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="../../assets/css/login.css">
</head>
<body>
    <div class="container">
        <h1>LOGIN</h1>
        <form method="POST" action="../controller/usuarioController.php?acao=logar" novalidate>
            <div class="form-group">
                <label for="email">EMAIL</label>
                <input type="text" id="email" name="email" placeholder="exemplo@email.com" required>
            </div>
            <div class="form-group">
                <label for="password">Senha</label>
                <input type="password" id="password" name="password" placeholder="********" minlength="8" required>
            </div>
            <?php if (isset($_SESSION['msg'])): ?>
                <p><?= htmlspecialchars($_SESSION['msg'], ENT_QUOTES, 'UTF-8'); ?></p>
                <?php unset($_SESSION['msg']); ?>
            <?php endif; ?>
            <div class="buttons">
                <a href="cadastrar.php" class="btn btn-new">Criar</a>
                <a href="../../index.php" class="btn btn-cancel">Cancelar</a>
                <button type="submit" class="btn btn-save">Login</button>
            </div>
        </form>
        <div class="footer">INFORMATICA 2 ANO</div>
    </div>
</body>
</html>
