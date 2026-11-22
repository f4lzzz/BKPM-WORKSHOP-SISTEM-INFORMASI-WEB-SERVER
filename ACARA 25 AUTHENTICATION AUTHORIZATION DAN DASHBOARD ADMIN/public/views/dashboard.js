
const BASE_URL = "http://localhost/BKPM%20WORKSHOP%20SISTEM%20INFORMASI%20WEB%20SERVER/ACARA%2025%20Authentication%20Authorization%20dan%20Dashboard%20Admin/public";

const token = localStorage.getItem("token");
const role = localStorage.getItem("role");
const username = localStorage.getItem("username");

const tabel = document.querySelector("#tabel-mahasiswa");
const totalMahasiswa = document.querySelector("#total-mahasiswa");
const status = document.querySelector("#status");

// Melindungi halaman dashboard
if (!token) {
    window.location.href = "login.html";
}

// Menampilkan informasi pengguna
document.querySelector("#nama-user").textContent = username || "Pengguna";
document.querySelector("#role-user").textContent = role || "Tidak diketahui";

// Menyembunyikan tombol CRUD jika role user
if (role !== "admin") {
    document.querySelectorAll(".admin-only").forEach((element) => {
        element.style.display = "none";
    });
}

// Mengambil data mahasiswa
async function muatDataMahasiswa() {
    tabel.innerHTML = `
        <tr>
            <td colspan="5">Memuat data mahasiswa...</td>
        </tr>
    `;

    try {
        const response = await fetch(`${BASE_URL}/mahasiswa`, {
            headers: {
                "Authorization": `Bearer ${token}`
            }
        });

        const hasil = await response.json();

        if (response.status === 401) {
            localStorage.clear();
            window.location.href = "login.html";
            return;
        }

        if (!response.ok || !hasil.success) {
            throw new Error(hasil.message);
        }

        const data = hasil.data.mahasiswa;

        totalMahasiswa.textContent = hasil.data.total;

        if (data.length === 0) {
            tabel.innerHTML = `
                <tr>
                    <td colspan="5">Belum ada data mahasiswa</td>
                </tr>
            `;
            return;
        }

        tabel.innerHTML = data.map((m, index) => `
            <tr>
                <td>${index + 1}</td>
                <td>${escapeHTML(m.nim)}</td>
                <td>${escapeHTML(m.nama)}</td>
                <td>${escapeHTML(m.prodi)}</td>
                ${role === "admin" ? `
                    <td>
                        <button class="edit-btn" onclick="ubahMahasiswa(${m.id})">
                            Ubah
                        </button>
                        <button class="delete-btn" onclick="hapusMahasiswa(${m.id})">
                            Hapus
                        </button>
                    </td>
                ` : ""}
            </tr>
        `).join("");

    } catch (error) {
        console.error(error);
        tabel.innerHTML = `
            <tr>
                <td colspan="5">Gagal memuat data mahasiswa</td>
            </tr>
        `;
        status.textContent = error.message;
    }
}

// Mengamankan teks yang ditampilkan di tabel
function escapeHTML(value) {
    return String(value).replace(/[&<>"']/g, (character) => ({
        "&": "&amp;",
        "<": "&lt;",
        ">": "&gt;",
        '"': "&quot;",
        "'": "&#039;"
    })[character]);
}

// Menambahkan mahasiswa
document.querySelector("#btn-tambah").addEventListener("click", async () => {
    const nim = prompt("Masukkan NIM:");
    if (nim === null || !nim.trim()) return;

    const nama = prompt("Masukkan nama mahasiswa:");
    if (nama === null || !nama.trim()) return;

    const prodi = prompt("Masukkan program studi:");
    if (prodi === null || !prodi.trim()) return;

    try {
        const response = await fetch(`${BASE_URL}/mahasiswa`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "Authorization": `Bearer ${token}`
            },
            body: JSON.stringify({
                nim: nim.trim(),
                nama: nama.trim(),
                prodi: prodi.trim()
            })
        });

        const hasil = await response.json();

        alert(hasil.message);

        if (response.ok && hasil.success) {
            muatDataMahasiswa();
        }

    } catch (error) {
        alert("Gagal menghubungi server: " + error.message);
    }
});

// Mengubah mahasiswa
async function ubahMahasiswa(id) {
    const nim = prompt("Masukkan NIM baru:");
    if (nim === null || !nim.trim()) return;

    const nama = prompt("Masukkan nama baru:");
    if (nama === null || !nama.trim()) return;

    const prodi = prompt("Masukkan program studi baru:");
    if (prodi === null || !prodi.trim()) return;

    try {
        const response = await fetch(`${BASE_URL}/mahasiswa?id=${id}`, {
            method: "PUT",
            headers: {
                "Content-Type": "application/json",
                "Authorization": `Bearer ${token}`
            },
            body: JSON.stringify({
                nim: nim.trim(),
                nama: nama.trim(),
                prodi: prodi.trim()
            })
        });

        const hasil = await response.json();

        alert(hasil.message);

        if (response.ok && hasil.success) {
            muatDataMahasiswa();
        }

    } catch (error) {
        alert("Gagal menghubungi server: " + error.message);
    }
}

// Menghapus mahasiswa
async function hapusMahasiswa(id) {
    const konfirmasi = confirm("Yakin ingin menghapus data mahasiswa ini?");

    if (!konfirmasi) return;

    try {
        const response = await fetch(`${BASE_URL}/mahasiswa?id=${id}`, {
            method: "DELETE",
            headers: {
                "Authorization": `Bearer ${token}`
            }
        });

        const hasil = await response.json();

        alert(hasil.message);

        if (response.ok && hasil.success) {
            muatDataMahasiswa();
        }

    } catch (error) {
        alert("Gagal menghubungi server: " + error.message);
    }
}

// Logout
document.querySelector("#btn-logout").addEventListener("click", async () => {
    try {
        await fetch(`${BASE_URL}/logout`, {
            method: "POST",
            headers: {
                "Authorization": `Bearer ${token}`
            }
        });
    } catch (error) {
        console.error("Logout API gagal:", error);
    } finally {
        localStorage.removeItem("token");
        localStorage.removeItem("role");
        localStorage.removeItem("username");

        window.location.href = "login.html";
    }
});

// Jalankan saat halaman dibuka
muatDataMahasiswa();
