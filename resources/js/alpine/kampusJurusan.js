document.addEventListener("alpine:init", () => {
    Alpine.data("kampusJurusan", () => ({
        cabangs: [],
        kampus: "",
        jurusan: "",
        jurusanList: [],

        initData(data) {
            this.cabangs = data;
        },

        updateJurusan() {
            const selected = this.cabangs.find(
                (c) => c.id == Number(this.kampus),
            );
            this.jurusanList = selected ? selected.jurusans : [];
            this.jurusan = "";
        },
    }));
});
