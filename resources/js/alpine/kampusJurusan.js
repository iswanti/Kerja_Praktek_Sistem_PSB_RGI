document.addEventListener("alpine:init", () => {
    Alpine.data("kampusJurusan", () => ({
        cabangs: [],
        kampus: "",
        jurusan: "",
        jurusanList: [],

        initData(data, selectedKampus = "", selectedJurusan = "") {
            this.cabangs = data;

            setTimeout(() => {
                this.kampus = selectedKampus ? String(selectedKampus) : "";

                this.setJurusanList();

                setTimeout(() => {
                    this.jurusan = selectedJurusan
                        ? String(selectedJurusan)
                        : "";
                }, 50);
            }, 50);
        },
        setJurusanList() {
            const selected = this.cabangs.find(
                (c) => String(c.id) === String(this.kampus),
            );

            this.jurusanList = selected ? selected.jurusans || [] : [];
        },

        updateJurusan() {
            this.setJurusanList();
            this.jurusan = "";
        },
    }));
});
