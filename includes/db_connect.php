<?php
// Arquivo de Conexão com o Banco de Dados (PDO)

// Configurações do banco de dados - Substitua com suas credenciais da Hostinger se necessário
define('DB_HOST', 'localhost');
define('DB_NAME', 'beefit_db');
define('DB_USER', 'root'); // Usuário padrão do XAMPP/WAMP, ajuste para a Hostinger
define('DB_PASS', '');     // Senha padrão do XAMPP/WAMP, ajuste para a Hostinger
define('DB_CHARSET', 'utf8mb4');

// DSN (Data Source Name)
$dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;

// Opções do PDO
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Lança exceções em caso de erro
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Retorna arrays associativos
    PDO::ATTR_EMULATE_PREPARES   => false,                  // Desativa a emulação de prepared statements
];

try {
    // Cria a instância do PDO
    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
} catch (\PDOException $e) {
    // Em caso de falha na conexão, exibe uma mensagem de erro genérica e encerra o script.
    // Em um ambiente de produção, o ideal é logar o erro em um arquivo, em vez de exibi-lo.
    error_log("Erro de Conexão com o Banco de Dados: " . $e->getMessage());
    die("Erro ao se conectar com o banco de dados. Por favor, tente novamente mais tarde.");
}

// --- Função para buscar as configurações do site ---
// Esta função busca todas as configurações e as retorna como um array associativo
// para fácil acesso em todo o site (ex: $settings['site_logo'])
function get_site_settings($pdo) {
    try {
        $stmt = $pdo->query("SELECT setting_key, setting_value FROM settings");
        $settings = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
        return $settings;
    } catch (\PDOException $e) {
        // Se houver um erro, retorna um array vazio para não quebrar o site
        error_log("Erro ao buscar configurações: " . $e->getMessage());
        return [];
    }
}

// Busca as configurações uma vez para serem usadas nas páginas
$settings = get_site_settings($pdo);
?>
