
const BASE_URL = "https://faulacara28.my.id";

const formLogin = document.querySelector("#form-login");
const message = document.querySelector("#message");
const buttonLogin = document.querySelector("#btn-login");

// Jika sudah login, arahkan ke dashboard
if (localStorage.getItem("token")) {
    window.location.href = "/views/dashboard.html";
}

// Membaca respons server dengan aman
async function bacaRespons(response) {
    const contentType = response.headers.get("content-type") || "";
    const text = await response.text();

    if (!contentType.includes("application/json")) {
        console.error("Respons bukan JSON:", text);
        throw new Error("Server mengirim respons bukan JSON. Periksa konfigurasi hosting.");
    }

    try {
        return JSON.parse(text);
    } catch (error) {
        throw new Error("Respons JSON dari server tidak valid.");
    }
}

// Proses login
formLogin.addEventListener("submit", async function (e) {
    e.preventDefault();

    const username = document.querySelector("#username").value.trim();
    const password = document.querySelector("#password").value;

    buttonLogin.disabled = true;
    buttonLogin.textContent = "Memproses...";
    message.textContent = "";

    try {
        const response = await fetch(`${BASE_URL}/login`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json"
            },
            body: JSON.stringify({
                username: username,
                password: password
            })
        });

        const hasil = await bacaRespons(response);

        if (!response.ok || !hasil.success) {
            throw new Error(hasil.message || "Login gagal.");
        }

        localStorage.setItem("token", hasil.data.token);
        localStorage.setItem("role", hasil.data.role);
        localStorage.setItem("username", hasil.data.username);

        window.location.href = "/views/dashboard.html";

    } catch (error) {
        console.error("Login error:", error);
        message.textContent = error.message;
        message.style.color = "red";

    } finally {
        buttonLogin.disabled = false;
        buttonLogin.textContent = "Login";
    }
});

// Registrasi
document.querySelector("#link-register").addEventListener("click", async function (e) {
    e.preventDefault();

    const username = prompt("Masukkan username baru:");
    if (!username) return;

    const email = prompt("Masukkan email:");
    if (!email) return;

    const password = prompt("Masukkan password (minimal 6 karakter):");
    if (!password) return;

    try {
        const response = await fetch(`${BASE_URL}/register`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json"
            },
            body: JSON.stringify({
                username: username,
                email: email,
                password: password
            })
        });

        const hasil = await bacaRespons(response);

        alert(hasil.message || "Registrasi selesai.");

    } catch (error) {
        console.error("Register error:", error);
        alert(error.message);
    }
});
