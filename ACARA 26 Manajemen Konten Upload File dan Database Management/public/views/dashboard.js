
const BASE_URL = "http://localhost/BKPM%20WORKSHOP%20SISTEM%20INFORMASI%20WEB%20SERVER/ACARA%2026%20Manajemen%20Konten%20Upload%20File%20dan%20Database%20Management/public";

const token = localStorage.getItem("token");
const role = localStorage.getItem("role");
const username = localStorage.getItem("username");

const tabel = document.querySelector("#tabel-mahasiswa");
const totalMahasiswa = document.querySelector("#total-mahasiswa");
const status = document.querySelector("#status");

if (!token) {
    window.location.href = "login.html";
}

document.querySelector("#nama-user").textContent = username || "Pengguna";
document.querySelector("#role-user").textContent = role || "Tidak diketahui";

if (role !== "admin") {
    document.querySelectorAll(".admin-only").forEach(element => {
        element.style.display = "none";
    });
}

// Pemilih foto
function pilihFoto() {
    return new Promise(resolve => {
        const input = document.createElement("input");

        input.type = "file";
        input.accept = ".jpg,.jpeg,.png,image/jpeg,image/png";

        input.addEventListener("change", () => {
            resolve(input.files[0] || null);
        });

        input.click();
    });
}

// Escape HTML
function escapeHTML(value) {
    return String(value).replace(/[&<>"']/g, character => ({
        "&": "&amp;",
        "<": "&lt;",
        ">": "&gt;",
        '"': "&quot;",
        "'": "&#039;"
    })[character]);
}

// Memuat data mahasiswa
async function muatDataMahasiswa() {
    tabel.innerHTML = `
        <tr><td colspan="6">Memuat data mahasiswa...</td></tr>
    `;

    try {
        const response = await fetch(`${BASE_URL}/mahasiswa`, {
            headers: {
                Authorization: `Bearer ${token}`
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
                <tr><td colspan="6">Belum ada data mahasiswa</td></tr>
            `;
            return;
        }

        tabel.innerHTML = data.map((m, index) => `
            <tr>
                <td>${index + 1}</td>
                <td>${escapeHTML(m.nim)}</td>
                <td>${escapeHTML(m.nama)}</td>
                <td>${escapeHTML(m.prodi)}</td>
                <td>
                    ${m.foto
                        ? `<img src="${BASE_URL}/uploads/${encodeURIComponent(m.foto)}"
                            alt="Foto mahasiswa"
                            width="65"
                            height="65"
                            style="object-fit:cover;border-radius:8px;">`
                        : "Tidak ada foto"
                    }
                </td>
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
            <tr><td colspan="6">Gagal memuat data mahasiswa</td></tr>
        `;
        status.textContent = error.message;
    }
}

// Tambah mahasiswa
document.querySelector("#btn-tambah").addEventListener("click", async () => {
    // Pemilih file dibuka langsung dari klik tombol
    const foto = await pilihFoto();

    const nim = prompt("Masukkan NIM:");
    if (nim === null || !nim.trim()) return;

    const nama = prompt("Masukkan nama mahasiswa:");
    if (nama === null || !nama.trim()) return;

    const prodi = prompt("Masukkan program studi:");
    if (prodi === null || !prodi.trim()) return;

    const formData = new FormData();

    formData.append("nim", nim.trim());
    formData.append("nama", nama.trim());
    formData.append("prodi", prodi.trim());

    if (foto) {
        formData.append("foto", foto);
    }

    try {
        const response = await fetch(`${BASE_URL}/mahasiswa`, {
            method: "POST",
            headers: {
                Authorization: `Bearer ${token}`
            },
            body: formData
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

// Ubah mahasiswa
async function ubahMahasiswa(id) {
    // Pemilih file dibuka langsung dari klik tombol
    const foto = await pilihFoto();

    const nim = prompt("Masukkan NIM baru:");
    if (nim === null || !nim.trim()) return;

    const nama = prompt("Masukkan nama baru:");
    if (nama === null || !nama.trim()) return;

    const prodi = prompt("Masukkan program studi baru:");
    if (prodi === null || !prodi.trim()) return;

    const formData = new FormData();

    formData.append("nim", nim.trim());
    formData.append("nama", nama.trim());
    formData.append("prodi", prodi.trim());

    if (foto) {
        formData.append("foto", foto);
    }

    try {
        const response = await fetch(
            `${BASE_URL}/mahasiswa/update?id=${id}`,
            {
                method: "POST",
                headers: {
                    Authorization: `Bearer ${token}`
                },
                body: formData
            }
        );

        const hasil = await response.json();

        alert(hasil.message);

        if (response.ok && hasil.success) {
            muatDataMahasiswa();
        }

    } catch (error) {
        alert("Gagal menghubungi server: " + error.message);
    }
}

// Hapus mahasiswa
async function hapusMahasiswa(id) {
    if (!confirm("Yakin ingin menghapus data mahasiswa ini?")) return;

    try {
        const response = await fetch(`${BASE_URL}/mahasiswa?id=${id}`, {
            method: "DELETE",
            headers: {
                Authorization: `Bearer ${token}`
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
                Authorization: `Bearer ${token}`
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

// Jalankan dashboard
muatDataMahasiswa();