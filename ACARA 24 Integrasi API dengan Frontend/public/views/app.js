const BASE_URL = "http://localhost/BKPM%20WORKSHOP%20SISTEM%20INFORMASI%20WEB%20SERVER/ACARA%2024%20Integrasi%20API%20dengan%20Frontend/public";

async function muatDataMahasiswa() {
    const tbody = document.querySelector("#tabel-mahasiswa tbody");

    tbody.innerHTML = `<tr><td colspan="3">Memuat data...</td></tr>`;

    try {
        const response = await fetch(`${BASE_URL}/mahasiswa`);

        if (!response.ok) {
            throw new Error("HTTP Error: " + response.status);
        }

        const hasil = await response.json();

        tbody.innerHTML = "";

        if (!hasil.success || hasil.data.length === 0) {
            tbody.innerHTML = `<tr><td colspan="3">Belum ada data</td></tr>`;
            return;
        }

        hasil.data.forEach((mhs) => {
            tbody.innerHTML += `
                <tr>
                    <td>${mhs.nim}</td>
                    <td>${mhs.nama}</td>
                    <td>${mhs.prodi}</td>
                </tr>
            `;
        });

    } catch (error) {
        console.error("Gagal memuat data:", error);
        tbody.innerHTML = `<tr><td colspan="3">Gagal memuat data mahasiswa</td></tr>`;
    }
}

document.addEventListener("DOMContentLoaded", muatDataMahasiswa);
