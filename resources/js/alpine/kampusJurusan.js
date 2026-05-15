document.addEventListener("alpine:init", () => {
    Alpine.data("kampusJurusan", () => ({
        cabangs: [],
        kampus: "",
        jurusan: "",
        jurusanList: [],

        initData(data, selectedKampus = "", selectedJurusan = "") {
            this.cabangs = data;
            this.kampus = selectedKampus ? String(selectedKampus) : "";

            this.setJurusanList();

            this.jurusan = selectedJurusan ? String(selectedJurusan) : "";
        },

        setJurusanList() {
            const selected = this.cabangs.find(
                (c) => String(c.id) === String(this.kampus),
            );

            this.jurusanList = selected
                ? selected.jurusans ||
                  selected.jurusan ||
                  selected.jurusan_cabang ||
                  []
                : [];
        },

        updateJurusan() {
            this.setJurusanList();
            this.jurusan = "";
        },
    }));
});
