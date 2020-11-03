<!-- Komponente für den Text eines Abschnitts von Projektantrag oder -dokumentation -->
<!-- Kann Text, Tabellen, Listen und Links enthalten -->
<!-- Nicht fertig; wird im Moment nicht verwendet -->

<template>
    <div>
        <div v-for="(val, i) in value" class="my-2">
            <textarea class="form-control" v-model="val.val" v-if="val.type==='text'"/>
            <div v-else-if="val.type==='table'" class="card">
                <div class="card-header">
                    <a href="#" @click.prevent="val.show = !val.show;">Tabelle anzeigen</a>
                    <div class="d-flex justify-content-between">
                        <label class="mr-2" :for="'tableCaption' + i">Tabellenname:</label>
                        <input type="text" class="form-control ml-2" :id="'tableCaption' + i" v-model="val.caption"/>
                    </div>
                </div>
                <div class="card-body" v-show="val.show">
                    <table-component :val="val"></table-component>
                </div>
            </div>
            <div v-else-if="val.type==='list'" class="card">
                <div class="card-header">
                    <a href="#" @click.prevent="val.show = !val.show;">Liste anzeigen</a>
                    <div class="d-flex justify-content-between">
                        <label class="mr-2" :for="'listOrder' + i">Aufzählungsart:</label>
                        <select class="form-control ml-2" :id="'listOrder' + i" v-model="val.order">
                            <!-- todo variable Aufzählung -->
                            <option value="unordered">Ungeordnet</option>
                            <option value="1">1</option>
                            <option value="a">a</option>
                            <option value="A">A</option>
                            <option value="i">i</option>
                            <option value="I">I</option>
                        </select>
                    </div>
                </div>
                <div class="card-body" v-show="val.show">
                    <list-component :val="val"></list-component>
                </div>
            </div>

            <a href="#" class="text-danger" @click.prevent="removeVal(i)">Wert entfernen</a>
            <hr>
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
                    this.value.push({
                        type: "table",
                        caption: "Neue Tabelle",
                        rows: [
                            {is_header: true, cols: [{text: "head1"}, {text: "head2"}]},
                            {is_header: false, cols: [{text: "col1"}, {text: "col2"}]}
                        ],
                        show: false
                    });
                    break;
                case "list":
                    this.value.push({
                        type: "list",
                        show: false,
                        order: "unordered",
                        items: [{text: "list item 1"}, {text: "list item 2"}]
                    });
                    break;
            }
        },

        removeVal(i) {
            this.value.splice(i, 1);
        }
    }
};
</script>
