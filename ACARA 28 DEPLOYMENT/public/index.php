
<?php

// Konfigurasi CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Menangani preflight request
if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
    http_response_code(200);
    exit;
}

// Mengambil path URL tanpa query string
$requestPath = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

// Halaman utama menampilkan login
if (
    $_SERVER["REQUEST_METHOD"] === "GET" &&
    ($requestPath === "/" || $requestPath === "")
) {
    header("Content-Type: text/html; charset=UTF-8");

    $loginPage = __DIR__ . "/views/login.html";

    if (file_exists($loginPage)) {
        readfile($loginPage);
    } else {
        http_response_code(404);
        echo "Halaman login tidak ditemukan.";
    }

    exit;
}

// Response API menggunakan JSON
header("Content-Type: application/json; charset=UTF-8");

// Membaca FormData pada request PUT
if ($_SERVER["REQUEST_METHOD"] === "PUT") {
    $contentType = $_SERVER["CONTENT_TYPE"] ?? "";

    if (stripos($contentType, "multipart/form-data") !== false) {
        preg_match('/boundary=(.*)$/', $contentType, $matches);

        if (!empty($matches[1])) {
            $boundary = trim($matches[1], "\"");
            $input = file_get_contents("php://input");
            $parts = explode("--" . $boundary, $input);

            foreach ($parts as $part) {
                $part = ltrim($part, "\r\n");

                if ($part === "" || $part === "--") {
                    continue;
                }

                $sections = explode("\r\n\r\n", $part, 2);

                if (count($sections) !== 2) {
                    continue;
                }

                $headers = $sections[0];
                $content = preg_replace("/\r\n$/", "", $sections[1]);

                if (!preg_match('/name="([^"]+)"/', $headers, $nameMatch)) {
                    continue;
                }

                $fieldName = $nameMatch[1];

                if (preg_match('/filename="([^"]*)"/', $headers, $fileMatch)) {
                    $fileName = $fileMatch[1];

                    if ($fileName === "") {
                        continue;
                    }

                    $tempFile = tempnam(sys_get_temp_dir(), "put_");
                    file_put_contents($tempFile, $content);

                    $_FILES[$fieldName] = [
                        "name" => $fileName,
                        "type" => "",
                        "tmp_name" => $tempFile,
                        "error" => UPLOAD_ERR_OK,
                        "size" => filesize($tempFile)
                    ];

                    register_shutdown_function(function () use ($tempFile) {
                        if (file_exists($tempFile)) {
                            unlink($tempFile);
                        }
                    });
                } else {
                    $_POST[$fieldName] = $content;
                }
            }
        }
    }
}

// Memanggil seluruh file backend dari luar public_html
$backendPath = dirname(__DIR__) . "/app/";

require_once $backendPath . "config/Database.php";

require_once $backendPath . "core/Response.php";
require_once $backendPath . "core/Router.php";
require_once $backendPath . "core/Auth.php";

require_once $backendPath . "models/User.php";
require_once $backendPath . "models/Mahasiswa.php";

require_once $backendPath . "controllers/AuthController.php";
require_once $backendPath . "controllers/MahasiswaController.php";

// Koneksi database
$database = new Database();
$db = $database->getConnection();

// Inisialisasi controller
$authController = new AuthController($db);
$mahasiswaController = new MahasiswaController($db);

// Inisialisasi router
$router = new Router();

// Endpoint authentication
$router->add("POST", "/register", [$authController, "register"]);
$router->add("POST", "/login", [$authController, "login"]);
$router->add("POST", "/logout", [$authController, "logout"]);

// Endpoint mahasiswa
$router->add("GET", "/mahasiswa", [$mahasiswaController, "index"]);
$router->add("POST", "/mahasiswa", [$mahasiswaController, "store"]);
$router->add("POST", "/mahasiswa/update", [$mahasiswaController, "update"]);
$router->add("PUT", "/mahasiswa", [$mahasiswaController, "update"]);
$router->add("DELETE", "/mahasiswa", [$mahasiswaController, "destroy"]);

// Menjalankan router
$router->dispatch();
