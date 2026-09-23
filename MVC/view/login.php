<?php session_start();

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>LOGIN</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>

<div class="container">
    <h1>LOGIN</h1>
    <form method="POST" action="../controller/login.php" novalidate>
        <div class="form-group">
            <label>EMAIL NE</label>
            <input type="text" name="email" required>
        </div>
        
        <div class="form-group">
            <label>Senha</label>
            <input type="text" name="password" placeholder="********" minlength="8" required>
        </div>
        
        <?php if(isset($_SESSION['msg'])){
            echo $_SESSION['msg'];
            unset($_SESSION['msg']);
        } ?>
        <div class="buttons">
            <button class="btn btn-save"><a href="cadastrar.php" class="cadastrar-link">Criar</a></button>
            <a href="../../index.php" class="btn btn-cancel">Cancelar</a>
            <button type="submit" class="btn btn-save">Login</button>
        </div>
    </form>
    <div class="footer">INFORMATICA 2 ANO</div>
</div>

</body>
</html>