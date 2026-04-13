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

        async getProvinsi() {
            const res = await fetch("/provinsi");
            const data = await res.json();
            this.provinsis = data.value;
        },

        async getKabupaten() {
            const res = await fetch(`/kabupaten/${this.provinsi}`);
            const data = await res.json();
            this.kabupatens = data.value;

            this.kecamatan = "";
            this.kecamatans = [];
        },

        async getKecamatan() {
            const res = await fetch(`/kecamatan/${this.kabupaten}`);
            const data = await res.json();
            this.kecamatans = data.value;

            this.kelurahan = "";
            this.kelurahans = [];
        },

        async getKelurahan() {
            const res = await fetch(`/kelurahan/${this.kecamatan}`);
            const data = await res.json();
            this.kelurahans = data.value;
        },
    }));
});
