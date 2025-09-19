<?php
session_start();

// 1. Desfaz todas as variáveis de sessão
$_SESSION = array();

// 2. Se for desejado destruir a sessão completamente, apague também o cookie de sessão.
// Nota: Isso destruirá a sessão e não apenas os dados da sessão!
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 3. Finalmente, destrói a sessão.
session_destroy();

// 4. Redireciona para a página de login
header('Location: login.php');
exit;
?>
