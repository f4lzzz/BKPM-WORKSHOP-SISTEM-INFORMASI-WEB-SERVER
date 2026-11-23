
const BASE_URL = "https://faulacara28.my.id";

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

// Area notifikasi Bootstrap
const areaNotifikasi = document.createElement("div");
areaNotifikasi.id = "notifikasi-dashboard";
areaNotifikasi.setAttribute("aria-live", "polite");
tabel.closest(".card")?.prepend(areaNotifikasi);

// Notifikasi
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

// Escape HTML untuk mencegah XSS
function escapeHTML(value) {
    return String(value ?? "").replace(/[&<>"']/g, character => ({
        "&": "&amp;",
        "<": "&lt;",
        ">": "&gt;",
        '"': "&quot;",
        "'": "&#039;"
    })[character]);
}

// Fetch dengan retry otomatis satu kali
// Retry hanya dilakukan jika terjadi kegagalan jaringan
async function fetchDenganRetry(url, options = {}) {
    let percobaan = 0;
    const maksimalPercobaan = 2;

    while (percobaan < maksimalPercobaan) {
        try {
            return await fetch(url, options);
        } catch (error) {
            percobaan++;

            if (percobaan >= maksimalPercobaan) {
                throw new Error(
                    "Koneksi jaringan gagal setelah dicoba dua kali. Periksa koneksi internet."
                );
            }

            console.warn(
                "Permintaan gagal. Mencoba ulang...",
                error
            );
        }
    }
}

// Membuat URL gambar
function buatURLGambar(foto) {
    if (!foto) return "";

    const namaFile = String(foto)
        .replace(/\\/g, "/")
        .split("/")
        .pop()
        .trim();

    return `${BASE_URL}/uploads/${encodeURIComponent(namaFile)}`;
}

// Memuat data mahasiswa
async function muatDataMahasiswa() {
    tabel.innerHTML = `
        <tr>
            <td colspan="6">Memuat data mahasiswa...</td>
        </tr>
    `;

    try {
        const response = await fetchDenganRetry(
            `${BASE_URL}/mahasiswa`,
            {
                method: "GET",
                headers: {
                    Authorization: `Bearer ${token}`
                }
            }
        );

        const hasil = await response.json();

        if (response.status === 401) {
            localStorage.removeItem("token");
            localStorage.removeItem("role");
            localStorage.removeItem("username");

            window.location.href = "login.html";
            return;
        }

        if (!response.ok || !hasil.success) {
            throw new Error(
                hasil.message || "Gagal memuat data mahasiswa."
            );
        }

        const data = hasil.data.mahasiswa || [];

        totalMahasiswa.textContent = hasil.data.total;

        status.textContent = "";

        if (data.length === 0) {
            tabel.innerHTML = `
                <tr>
                    <td colspan="6">Belum ada data mahasiswa</td>
                </tr>
            `;
            return;
        }

        tabel.innerHTML = data.map((m, index) => {
            const urlFoto = buatURLGambar(m.foto);

            return `
                <tr>
                    <td>${index + 1}</td>

                    <td>${escapeHTML(m.nim)}</td>

                    <td>${escapeHTML(m.nama)}</td>

                    <td>${escapeHTML(m.prodi)}</td>

                    <td>
                        ${
                            urlFoto
                                ? `
                                    <img
                                        src="${escapeHTML(urlFoto)}"
                                        alt="Foto ${escapeHTML(m.nama)}"
                                        width="65"
                                        height="65"
                                        loading="lazy"
                                        style="object-fit:cover;border-radius:8px;"
                                        onerror="this.onerror=null;this.style.display='none';this.nextElementSibling.style.display='inline';"
                                    >

                                    <span style="display:none;color:#dc3545;">
                                        Foto gagal dimuat
                                    </span>
                                `
                                : "Tidak ada foto"
                        }
                    </td>

                    ${
                        role === "admin"
                            ? `
                                <td>
                                    <button
                                        class="edit-btn"
                                        onclick="ubahMahasiswa(${Number(m.id)})"
                                    >
                                        Ubah
                                    </button>

                                    <button
                                        class="delete-btn"
                                        onclick="hapusMahasiswa(${Number(m.id)})"
                                    >
                                        Hapus
                                    </button>
                                </td>
                            `
                            : ""
                    }
                </tr>
            `;
        }).join("");

    } catch (error) {
        console.error("Kesalahan memuat mahasiswa:", error);

        tabel.innerHTML = `
            <tr>
                <td colspan="6">
                    Gagal memuat data mahasiswa.
                    Silakan coba kembali.
                </td>
            </tr>
        `;

        status.textContent = error.message;

        tampilkanNotifikasi(
            error.message,
            "danger"
        );
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
        const response = await fetch(
            `${BASE_URL}/mahasiswa?id=${id}`,
            {
                method: "DELETE",
                headers: {
                    Authorization: `Bearer ${token}`
                }
            }
        );

        const hasil = await response.json();

        if (response.status === 401) {
            localStorage.removeItem("token");
            localStorage.removeItem("role");
            localStorage.removeItem("username");

            window.location.href = "login.html";
            return;
        }

        if (!response.ok || !hasil.success) {
            throw new Error(
                hasil.message || "Gagal menghapus mahasiswa."
            );
        }

        tampilkanNotifikasi(
            "Berhasil! Data mahasiswa telah dihapus.",
            "success"
        );

        await muatDataMahasiswa();

    } catch (error) {
        console.error("Kesalahan menghapus mahasiswa:", error);

        tampilkanNotifikasi(
            "Gagal menghapus data: " + error.message,
            "danger"
        );

    } finally {
        // Aktifkan kembali tombol jika masih tersedia
        if (tombolHapus && tombolHapus.isConnected) {
            tombolHapus.disabled = false;
            tombolHapus.textContent = "Hapus";
        }
    }
}

// Logout
document.querySelector("#btn-logout")?.addEventListener(
    "click",
    async () => {
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
    }
);

// Jalankan dashboard
muatDataMahasiswa();
