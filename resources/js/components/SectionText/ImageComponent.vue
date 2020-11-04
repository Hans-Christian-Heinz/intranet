<!-- Komponente für ein Bild als Teil der Komponente section-text -->

<template>
    <div>
        <div class="row my-1">
            <div class="col-4">
                <button type="button" class="btn btn-outline-secondary dropdown-toggle" :id="name + number + 'dropdownImgButton'"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Bild
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownImgButton">
                    <div v-for="(image, i) in images" class="dropdown-item radio-group">
                        <input type="radio" v-model="img.path" :value="image" class="radioImage" :id="name + number + 'radio' + i"/>
                        <label :for="name + number + 'radio' + i">
                            <img :src="image" height="150" width="240" alt="Im gespeicherten Dateipfad liegt keine Bilddatei."/>
                        </label>
                    </div>
                </div>
            </div>
            <div class="col-8">
                <img :src="img.path" height="200" width="400" alt="kein Bild ausgewählt"/>
            </div>
        </div>
        <div class="row my-1">
            <label :for="name + number + 'footnote'" class="col-4">Fußnote</label>
            <input type="text" :id="name + number + 'footnote'" class="form-control col-8" v-model="img.footnote"/>
        </div>
        <div class="row my-1">
            <label :for="name + number + 'width'" class="col-4">Breite in mm</label>
            <input type="number" :id="name + number + 'width'" class="form-control col-8" min="10" max="170" v-model="img.width"/>
        </div>
        <div class="row my-1">
            <label :for="name + number + 'height'" class="col-4">Höhe in mm</label>
            <input type="number" :id="name + number + 'height'" class="form-control col-8" min="10" max="247" v-model="img.height"/>
        </div>
    </div>
</template>
<script>
export default {
    props: ["val", "available_images", "name", "number"],

    data() {
        return {
            img: {
                path: "",
                footnote: "",
                height: "10mm",
                width: "10mm"
            },
            images: []
        };
    },

    mounted() {
        this.img = this.val;
        this.images = JSON.parse(this.available_images);
    }
};
</script>
