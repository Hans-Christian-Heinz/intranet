<!-- Komponente für den Text eines Abschnitts von Projektantrag oder -dokumentation -->
<!-- Kann Text, Tabellen, Listen und Links enthalten -->
<!-- Nicht fertig; wird im Moment nicht verwendet -->

<!-- todo fertigstellen -->
<!-- Komponente selbst ist eigentlich fertig; das entsprechende Ergebnis muss noch auf Serverseite verwendet werden. -->
<!-- (Validierung und Formatieren beim Drucken) -->
<template>
    <div>
        <!-- Der Wert, der tatsächlich in der Datenbnak hinterlegt wird -->
        <input type="hidden" :form="form" :name="name" v-model="encodedValue"/>
        <!-- input, der dem Validator sagt, dass diese vue-Komponente verwendet wurde -->
        <input type="hidden" :form="form" :name="name + '_is_stc'" value="1"/>

        <div v-for="(val, i) in orderedComponents" class="my-2">
            <div v-if="val.type==='text'" class="card" :key="name + i + val.changed">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <a href="#" @click.prevent="showCard(val)">Text anzeigen</a>
                        <div v-if="!disable">
                            <label :for="name + i + 'Sequence'">Reihenfolge: </label>
                            <input type="number" class="border-0" style="width:3em" min="0" @change="changeSequence($event, val)"
                                   :max="value.length - 1" :id="name + i + 'Sequence'" :value="val.sequence"/>
                        </div>
                    </div>
                </div>
                <div class="card-body" v-show="val.show">
                    <textarea class="form-control" :disabled="!!disable" v-model="val.val"/>
                </div>
            </div>
            <div v-else-if="val.type==='table'" class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <a href="#" @click.prevent="showCard(val)">Tabelle anzeigen</a>
                        <div v-if="!disable">
                            <label :for="name + i + 'Sequence'">Reihenfolge: </label>
                            <input type="number" class="border-0" style="width:3em" min="0" @change="changeSequence($event, val)"
                                   :max="value.length - 1" :id="name + i + 'Sequence'" :value="val.sequence"/>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <label class="mr-2" :for="name + 'TableCaption' + i">Tabellenname:</label>
                        <input type="text" class="form-control ml-2" :disabled="!!disable" :id="name + 'TableCaption' + i" v-model="val.caption"/>
                    </div>
                </div>
                <div class="card-body" v-show="val.show">
                    <table-component :disable="disable" :val="val" :name="name" :number="i"></table-component>
                </div>
            </div>
            <div v-else-if="val.type==='list'" class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <a href="#" @click.prevent="showCard(val)">Liste anzeigen</a>
                        <div v-if="!disable">
                            <label :for="name + i + 'Sequence'">Reihenfolge: </label>
                            <input type="number" class="border-0" style="width:3em" min="0" @change="changeSequence($event, val)"
                                   :max="value.length - 1" :id="name + i + 'Sequence'" :value="val.sequence"/>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <label class="mr-2" :for="name + 'ListOrder' + i">Aufzählungsart:</label>
                        <select :disabled="!!disable" class="form-control ml-2" :id="name + 'ListOrder' + i" v-model="val.order">
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
                    <list-component :disable="disable" :val="val" :name="name" :number="i"></list-component>
                </div>
            </div>
            <div v-else-if="val.type==='link'" class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <a href="#" @click.prevent="showCard(val)">Link anzeigen</a>
                        <div v-if="!disable">
                            <label :for="name + i + 'Sequence'">Reihenfolge: </label>
                            <input type="number" class="border-0" style="width:3em" min="0" @change="changeSequence($event, val)"
                                   :max="value.length - 1" :id="name + i + 'Sequence'" :value="val.sequence"/>
                        </div>
                    </div>
                </div>
                <div class="card-body" v-show="val.show">
                    <link-component :disable="disable" :val="val" :name="name" :number="i" :available_sections="available_sections"></link-component>
                </div>
            </div>
            <div v-else-if="val.type==='img'" class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <a href="#" @click.prevent="showCard(val)">Bild anzeigen</a>
                        <div v-if="!disable">
                            <label :for="name + i + 'Sequence'">Reihenfolge: </label>
                            <input type="number" class="border-0" style="width:3em" min="0" @change="changeSequence($event, val)"
                                   :max="value.length - 1" :id="name + i + 'Sequence'" :value="val.sequence"/>
                        </div>
                    </div>
                </div>
                <div class="card-body" v-show="val.show">
                    <image-component :disable="disable" :val="val" :name="name" :number="i"
                                     :prefix="img_prefix" :available_images="available_images"></image-component>
                </div>
            </div>

            <a href="#" v-if="!disable" class="text-danger" @click.prevent="removeVal(i)">Komponente entfernen</a>
            <hr>
        </div>
        <div v-if="!disable" class="d-flex justify-content-end my-2">
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
    props: ["text", "available_images", "available_sections", "name", "form", "disable", "img_prefix"],

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
                this.value[i].changed = 0;
            }
        }
        else {
            this.value = [{type: "text", val: "", show: true, sequence: 0, changed: 0}];
        }
    },

    computed: {
        orderedComponents: function() {
            return _.orderBy(this.value, 'sequence');
        },

        encodedValue: function() {
            let res = [];
            this.orderedComponents.forEach(function(comp) {
                res.push(Object.assign({}, comp));
            });

            res.forEach(function(val) {
                delete val.show;
                delete val.sequence;
                delete val.changed;
            });
            return JSON.stringify(res);
        },
    },

    methods: {
        showCard(val) {
            val.show = !val.show;
            val.changed++;
            //nicht sicer, warum das :key Attr nicht funktioniert;
            this.$forceUpdate();
        },

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
                        height: 10,
                        width: 10,
                        sequence: this.value.length
                    });
                    break;
            }
        },

        removeVal(i) {
            let seq = this.value[i].sequence;
            this.value.splice(i, 1);
            this.value.forEach(function(val) {
                if (val.sequence > seq) {
                    val.sequence--;
                }
            })
        },

        changeSequence(e, val) {
            let old = val.sequence;
            let newVal = e.target.value;
            if (newVal >= 0 && newVal < this.value.length) {
                //nach unten verschieben
                if (newVal > old) {
                    this.value.forEach(temp => {
                        if (temp.sequence > old && temp.sequence <= newVal) {
                            temp.sequence--;
                            temp.show = false;
                            temp.changed++;
                        }
                    });
                }
                //nach oben verschieben
                if (newVal < old) {
                    this.value.forEach(temp => {
                        if (temp.sequence < old && temp.sequence >= newVal) {
                            temp.sequence++;
                            temp.show = false;
                            temp.changed++;
                        }
                    });
                }

                val.show = false;
                val.sequence = newVal;
                val.changed++;
                this.$forceUpdate();
            }
            else {
                e.target.value = old;
            }
        }
    }
};
</script>
