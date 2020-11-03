<!-- Komponente für den Text eines Abschnitts von Projektantrag oder -dokumentation -->
<!-- Kann Text, Tabellen, Listen und Links enthalten -->
<!-- Nicht fertig; wird nicht verwendet -->

<template>
    <div>
        <div v-for="val in value" class="my-2">
            <textarea class="form-control" v-model="val.val" v-if="val.type==='text'"/>
            <div v-else-if="val.type==='table'">
                <a href="#" @click.prevent="val.show = !val.show;">Tabelle anzeigen</a>
                <table-component v-show="val.show" :val="val"></table-component>
            </div>
            <!-- todo list-component, link-component, image-component -->
        </div>
        <div class="d-flex justify-content-end my-2">
            <!-- todo variable Aufzählung -->
            <select id="selectContentType" class="form-control w-25 mx-2" v-model="typeToAdd">
                <option value="text">Text</option>
                <option value="table">Tabelle</option>
                <option value="list">Liste</option>
                <option value="link">Link</option>
                <option value="img">Bild</option>
            </select>
            <button class="btn btn-small btn-outline-primary mx-2" @click.prevent="addVal()">Wert hinzufügen</button>
        </div>
    </div>
</template>
<script>
export default {
    props: ["text"],

    data() {
        return {
            value: [],
            typeToAdd: "text"
        };
    },

    mounted() {
        if (this.text) {
            this.value = JSON.parse(this.text);
        }
        else {
            this.value = [{type: "text", val: ""}];
        }
    },

    computed: {

    },

    methods: {
        addVal() {
            switch (this.typeToAdd) {
                case "text":
                    this.value.push({type: "text", val: ""});
                    break;
                case "table":
                    this.value.push({type: "table", caption: "", rows: [], show: false});
                    break;
            }
        }
    }
};
</script>
