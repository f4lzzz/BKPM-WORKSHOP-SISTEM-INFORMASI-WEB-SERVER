
const BASE_URL = "http://localhost/BKPM%20WORKSHOP%20SISTEM%20INFORMASI%20WEB%20SERVER/ACARA%2027%20INTEGRASI%20BACKEND%20FRONTEND/public";

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

// Membuat area notifikasi Bootstrap
const areaNotifikasi = document.createElement("div");
areaNotifikasi.id = "notifikasi-dashboard";
areaNotifikasi.setAttribute("aria-live", "polite");
tabel.closest(".card")?.prepend(areaNotifikasi);

// Menampilkan notifikasi
function tampilkanNotifikasi(pesan, jenis) {
    areaNotifikasi.innerHTML = "";

    const alert = document.createElement("div");
    alert.className = `alert alert-${jenis} alert-dismissible fade show`;
    alert.setAttribute("role", "alert");
    alert.appendChild(document.createTextNode(pesan));

    const tombol = document.createElement("button");
    tombol.type = "button";
    tombol.className = "btn-close";
    tombol.setAttribute("data-bs-dismiss", "alert");
    tombol.setAttribute("aria-label", "Tutup");

    alert.appendChild(tombol);
    areaNotifikasi.appendChild(alert);
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
            throw new Error(hasil.message || "Gagal memuat data");
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
                        <button class="edit-btn" onclick="ubahMahasiswa(${Number(m.id)})">
                            Ubah
                        </button>

                        <button class="delete-btn" onclick="hapusMahasiswa(${Number(m.id)})">
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
        tampilkanNotifikasi(error.message, "danger");
    }
}

// Tombol tambah mahasiswa
document.querySelector("#btn-tambah")?.addEventListener("click", () => {
    window.location.href = "form_tambah.html";
});

// Tombol ubah mahasiswa
function ubahMahasiswa(id) {
    window.location.href = `form_edit.html?id=${id}`;
}

// Hapus mahasiswa
async function hapusMahasiswa(id) {
    if (!confirm("Yakin ingin menghapus data mahasiswa ini?")) {
        return;
    }

    const tombolHapus = document.querySelector(
        `button.delete-btn[onclick="hapusMahasiswa(${id})"]`
    );

    if (tombolHapus) {
        tombolHapus.disabled = true;
        tombolHapus.textContent = "Menghapus...";
    }

    try {
        const response = await fetch(`${BASE_URL}/mahasiswa?id=${id}`, {
            method: "DELETE",
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
            throw new Error(hasil.message || "Gagal menghapus mahasiswa.");
        }

        tampilkanNotifikasi(
            "Berhasil! Data mahasiswa telah dihapus.",
            "success"
        );

        await muatDataMahasiswa();

    } catch (error) {
        tampilkanNotifikasi(
            "Gagal menghapus data: " + error.message,
            "danger"
        );

        if (tombolHapus) {
            tombolHapus.disabled = false;
            tombolHapus.textContent = "Hapus";
        }
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
