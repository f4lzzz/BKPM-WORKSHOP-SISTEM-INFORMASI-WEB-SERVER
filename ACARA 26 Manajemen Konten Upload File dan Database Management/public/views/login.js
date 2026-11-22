
const BASE_URL = "http://localhost/BKPM%20WORKSHOP%20SISTEM%20INFORMASI%20WEB%20SERVER/ACARA%2025%20Authentication%20Authorization%20dan%20Dashboard%20Admin/public";

const formLogin = document.querySelector("#form-login");
const message = document.querySelector("#message");
const buttonLogin = document.querySelector("#btn-login");

// Jika sudah login, arahkan ke dashboard
if (localStorage.getItem("token")) {
    window.location.href = "dashboard.html";
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
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                username: username,
                password: password
            })
        });

        const hasil = await response.json();

        if (!response.ok || !hasil.success) {
            throw new Error(hasil.message || "Login gagal");
        }

        localStorage.setItem("token", hasil.data.token);
        localStorage.setItem("role", hasil.data.role);
        localStorage.setItem("username", hasil.data.username);

        window.location.href = "dashboard.html";

    } catch (error) {
        message.textContent = error.message;
        message.style.color = "red";
    } finally {
        buttonLogin.disabled = false;
        buttonLogin.textContent = "Login";
    }
});

// Link registrasi
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
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                username: username,
                email: email,
                password: password
            })
        });

        const hasil = await response.json();

        alert(hasil.message);

    } catch (error) {
        alert("Gagal menghubungi server: " + error.message);
    }
});
    