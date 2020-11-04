<!-- Komponente für den Text eines Abschnitts von Projektantrag oder -dokumentation -->
<!-- Kann Text, Tabellen, Listen und Links enthalten -->
<!-- Nicht fertig; wird im Moment nicht verwendet -->

<template>
    <div>
        <div v-for="(val, i) in orderedComponents" class="my-2">
            <div v-if="val.type==='text'" class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <a href="#" @click.prevent="val.show = !val.show;">Text anzeigen</a>
                        <div>
                            <label :for="name + i + 'Sequence'">Reihenfolge: </label>
                            <input type="number" class="border-0" style="width:3em" min="0" @change="changeSequence($event, val)"
                                   :max="value.length - 1" :id="name + i + 'Sequence'" :value="val.sequence"/>
                        </div>
                    </div>
                </div>
                <div class="card-body" v-show="val.show">
                    <textarea class="form-control" v-model="val.val"/>
                </div>
            </div>
            <div v-else-if="val.type==='table'" class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <a href="#" @click.prevent="val.show = !val.show;">Tabelle anzeigen</a>
                        <div>
                            <label :for="name + i + 'Sequence'">Reihenfolge: </label>
                            <input type="number" class="border-0" style="width:3em" min="0" @change="changeSequence($event, val)"
                                   :max="value.length - 1" :id="name + i + 'Sequence'" :value="val.sequence"/>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <label class="mr-2" :for="name + 'TableCaption' + i">Tabellenname:</label>
                        <input type="text" class="form-control ml-2" :id="name + 'TableCaption' + i" v-model="val.caption"/>
                    </div>
                </div>
                <div class="card-body" v-show="val.show">
                    <table-component :val="val" :name="name" :number="i"></table-component>
                </div>
            </div>
            <div v-else-if="val.type==='list'" class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <a href="#" @click.prevent="val.show = !val.show;">Liste anzeigen</a>
                        <div>
                            <label :for="name + i + 'Sequence'">Reihenfolge: </label>
                            <input type="number" class="border-0" style="width:3em" min="0" @change="changeSequence($event, val)"
                                   :max="value.length - 1" :id="name + i + 'Sequence'" :value="val.sequence"/>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <label class="mr-2" :for="name + 'ListOrder' + i">Aufzählungsart:</label>
                        <select class="form-control ml-2" :id="name + 'ListOrder' + i" v-model="val.order">
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
                    <list-component :val="val" :name="name" :number="i"></list-component>
                </div>
            </div>
            <div v-else-if="val.type==='link'" class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <a href="#" @click.prevent="val.show = !val.show;">Link anzeigen</a>
                        <div>
                            <label :for="name + i + 'Sequence'">Reihenfolge: </label>
                            <input type="number" class="border-0" style="width:3em" min="0" @change="changeSequence($event, val)"
                                   :max="value.length - 1" :id="name + i + 'Sequence'" :value="val.sequence"/>
                        </div>
                    </div>
                </div>
                <div class="card-body" v-show="val.show">
                    <link-component :val="val" :name="name" :number="i" :available_sections="available_sections"></link-component>
                </div>
            </div>
            <div v-else-if="val.type==='img'" class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <a href="#" @click.prevent="val.show = !val.show;">Bild anzeigen</a>
                        <div>
                            <label :for="name + i + 'Sequence'">Reihenfolge: </label>
                            <input type="number" class="border-0" style="width:3em" min="0" @change="changeSequence($event, val)"
                                   :max="value.length - 1" :id="name + i + 'Sequence'" :value="val.sequence"/>
                        </div>
                    </div>
                </div>
                <div class="card-body" v-show="val.show">
                    <image-component :val="val" :name="name" :number="i" :available_images="available_images"></image-component>
                </div>
            </div>

            <a href="#" class="text-danger" @click.prevent="removeVal(i)">Komponente entfernen</a>
            <hr>
        </div>
        <div class="d-flex justify-content-end my-2">
            <!-- todo variable Aufzählung -->
            <select :id="name + 'SelectContentType'" class="form-control w-25 mx-2" v-model="typeToAdd">
                <option value="text">Text</option>
                <option value="table">Tabelle</option>
                <option value="list">Liste</option>
                <option value="link">Link</option>
                <option value="img">Bild</option>
            </select>
            <button class="btn btn-small btn-outline-primary mx-2" @click.prevent="addVal()">Komponente hinzufügen</button>
        </div>
    </div>
</template>
<script>
export default {
    props: ["text", "available_images", "available_sections", "name"],

    data() {
        return {
            value: [],
            typeToAdd: "text"
        };
    },

    mounted() {
        if (this.text) {
            this.value = JSON.parse(this.text);
            for (let i = 0; i < this.value.length; i++) {
                this.value[i].show = i === 0;
                this.value[i].sequence = i;
            }
        }
        else {
            this.value = [{type: "text", val: "", show: true, sequence: 0}];
        }
    },

    computed: {
        orderedComponents: function() {
            return _.orderBy(this.value, 'sequence');
        }
    },

    methods: {
        addVal() {
            switch (this.typeToAdd) {
                case "text":
                    this.value.push({
                        type: "text",
                        val: "",
                        show: false,
                        sequence: this.value.length
                    });
                    break;
                case "table":
                    this.value.push({
                        type: "table",
                        caption: "Neue Tabelle",
                        rows: [
                            {is_header: true, cols: [{text: "head1"}, {text: "head2"}]},
                            {is_header: false, cols: [{text: "col1"}, {text: "col2"}]}
                        ],
                        show: false,
                        sequence: this.value.length
                    });
                    break;
                case "list":
                    this.value.push({
                        type: "list",
                        show: false,
                        order: "unordered",
                        items: [{text: "list item 1"}, {text: "list item 2"}],
                        sequence: this.value.length
                    });
                    break;
                case "link":
                    this.value.push({
                        type: "link",
                        show: false,
                        target: "",
                        text: "",
                        sequence: this.value.length
                    });
                    break;
                case "img":
                    this.value.push({
                        type: "img",
                        show: false,
                        path: "",
                        footnote: "",
                        height: "10mm",
                        width: "10mm",
                        sequence: this.value.length
                    });
                    break;
            }
        },

        removeVal(i) {
            this.value.splice(i, 1);
        },

        changeSequence(e, val) {
            let old = val.sequence;
            let newVal = e.target.value;
            if (newVal >= 0 && newVal < this.value.length - 1) {
                //nach unten verschieben
                if (newVal > old) {
                    this.value.forEach(temp => {
                        if (temp.sequence > old && temp.sequence <= newVal) {
                            temp.sequence--;
                            temp.show = false;
                        }
                    });
                }
                //nach oben verschieben
                if (newVal < old) {
                    this.value.forEach(temp => {
                        if (temp.sequence < old && temp.sequence >= newVal) {
                            temp.sequence++;
                            temp.show = false;
                        }
                    });
                }

                val.show = false;
                val.sequence = newVal;
            }
            else {
                e.target.value = old;
            }
        }
    }
};
</script>
