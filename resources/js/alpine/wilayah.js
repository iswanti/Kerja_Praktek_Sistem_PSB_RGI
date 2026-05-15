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

        async initWilayah(idAlamat) {
            await this.getProvinsi();

            if (!idAlamat) return;

            idAlamat = String(idAlamat);

            const kodeProvinsi = idAlamat.substring(0, 2);
            const kodeKabupaten = idAlamat.substring(0, 4);
            const kodeKecamatan = idAlamat.substring(0, 6);
            const kodeKelurahan = idAlamat;

            this.provinsi = kodeProvinsi;
            await this.getKabupaten(false);

            this.kabupaten = kodeKabupaten;
            await this.getKecamatan(false);

            const kecamatanCocok = this.kecamatans.find(
                (k) => String(k.code).trim() === String(kodeKecamatan).trim(),
            );

            this.kecamatan = kecamatanCocok
                ? String(kecamatanCocok.code).trim()
                : "";
            this.kecamatan_nama = kecamatanCocok ? kecamatanCocok.name : "";

            await this.$nextTick();

            await this.getKelurahan(false);

            const kelurahanCocok = this.kelurahans.find(
                (k) => String(k.code) === String(kodeKelurahan),
            );

            this.kelurahan = kelurahanCocok ? String(kelurahanCocok.code) : "";
            this.kelurahan_nama = kelurahanCocok ? kelurahanCocok.name : "";

            this.provinsi_nama =
                this.provinsis.find(
                    (p) => String(p.code) === String(this.provinsi),
                )?.name || "";

            this.kabupaten_nama =
                this.kabupatens.find(
                    (k) => String(k.code) === String(this.kabupaten),
                )?.name || "";
        },

        async getProvinsi() {
            const res = await fetch("/provinsi");
            this.provinsis = await res.json();
        },

        async getKabupaten(reset = true) {
            if (!this.provinsi) return;

            const res = await fetch(`/kabupaten/${this.provinsi}`);
            this.kabupatens = await res.json();

            if (reset) {
                this.kabupaten = "";
                this.kecamatan = "";
                this.kelurahan = "";
                this.kecamatans = [];
                this.kelurahans = [];
            }
        },

        async getKecamatan(reset = true) {
            if (!this.kabupaten) return;

            const res = await fetch(`/kecamatan/${this.kabupaten}`);
            this.kecamatans = await res.json();

            if (reset) {
                this.kecamatan = "";
                this.kelurahan = "";
                this.kelurahans = [];
            }
        },

        async getKelurahan(reset = true) {
            if (!this.kecamatan) return;

            const res = await fetch(`/kelurahan/${this.kecamatan}`);
            this.kelurahans = await res.json();

            if (reset) {
                this.kelurahan = "";
            }
        },

        pilihProvinsi() {
            const selected = this.provinsis.find(
                (p) => String(p.code) === String(this.provinsi),
            );
            this.provinsi_nama = selected?.name || "";

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

        pilihKabupaten() {
            const selected = this.kabupatens.find(
                (k) => String(k.code) === String(this.kabupaten),
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

        pilihKecamatan() {
            const selected = this.kecamatans.find(
                (k) => String(k.code) === String(this.kecamatan),
            );
            this.kecamatan_nama = selected?.name || "";

            this.kelurahan = "";
            this.kelurahan_nama = "";
            this.kelurahans = [];

            this.getKelurahan();
        },

        pilihKelurahan() {
            const selected = this.kelurahans.find(
                (k) => String(k.code) === String(this.kelurahan),
            );
            this.kelurahan_nama = selected?.name || "";
        },
    }));
});
