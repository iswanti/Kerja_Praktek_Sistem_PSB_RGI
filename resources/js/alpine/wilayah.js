document.addEventListener("alpine:init", () => {
    Alpine.data("wilayah", () => ({
        provinsis: [],
        kabupatens: [],
        kecamatans: [],
        kelurahans: [],

        provinsi: "",
        kabupaten: "",
        kecamatan: "",
        kelurahan: "",

        provinsi_nama: "",
        kabupaten_nama: "",
        kecamatan_nama: "",
        kelurahan_nama: "",

        async getProvinsi() {
            const res = await fetch("/provinsi");
            const data = await res.json();
            this.provinsis = data.value;
        },

        pilihProvinsi() {
            const selected = this.provinsis.find((p) => p.id == this.provinsi);
            this.provinsi_nama = selected?.name || "";

            // RESET TURUNAN
            this.kabupaten = "";
            this.kecamatan = "";
            this.kelurahan = "";

            this.kabupaten_nama = "";
            this.kecamatan_nama = "";
            this.kelurahan_nama = "";

            this.kabupatens = [];
            this.kecamatans = [];
            this.kelurahans = [];

            this.getKabupaten();
        },

        async getKabupaten() {
            const res = await fetch(`/kabupaten/${this.provinsi}`);
            const data = await res.json();
            this.kabupatens = data.value;

            this.kecamatan = "";
            this.kecamatans = [];
        },

        pilihKabupaten() {
            const selected = this.kabupatens.find(
                (k) => k.id == this.kabupaten,
            );
            this.kabupaten_nama = selected?.name || "";

            this.kecamatan = "";
            this.kelurahan = "";

            this.kecamatan_nama = "";
            this.kelurahan_nama = "";

            this.kecamatans = [];
            this.kelurahans = [];

            this.getKecamatan();
        },

        async getKecamatan() {
            const res = await fetch(`/kecamatan/${this.kabupaten}`);
            const data = await res.json();
            this.kecamatans = data.value;

            this.kelurahan = "";
            this.kelurahans = [];
        },

        pilihKecamatan() {
            const selected = this.kecamatans.find(
                (k) => k.id == this.kecamatan,
            );
            this.kecamatan_nama = selected?.name || "";

            this.kelurahan = "";
            this.kelurahan_nama = "";

            this.kelurahans = [];

            this.getKelurahan();
        },

        async getKelurahan() {
            const res = await fetch(`/kelurahan/${this.kecamatan}`);
            const data = await res.json();
            this.kelurahans = data.value;
        },

        pilihKelurahan() {
            const selected = this.kelurahans.find(
                (k) => k.id == this.kelurahan,
            );
            this.kelurahan_nama = selected?.name || "";
        },
    }));
});
