<!-- Teil des Formulars für Projektanträge und Dokumentationen: Inputs für Abschnitte, die als Tabellen dargestellt werden -->

<template>
    <div>
        <table class="table border-0">
            <thead>
            <tr>
                <th>Reihenfolge</th>
                <th v-for="t in tpl">{{ t.name }}</th>
                <th>Löschen</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="v in orderedValues">
                <!-- Reihenfolge der Einträge liegt immer vor -->
                <td>
                    <input type="number" class="form-control" style="width: 4em" min="0" @change="changeNumber($event, v)"
                           :max="values.length - 1" :value="v.number" :disabled="!!disable" required/>
                </td>
                <td v-for="t in tpl">
                    <input v-if="t.type === 'number'" :disabled="!!disable" :step="t.step" :min="t.min"
                           :type="t.type" class="form-control" v-model="v[t.name]" :required="t.required"/>
                    <input v-else :type="t.type" :disabled="!!disable" class="form-control" v-model="v[t.name]" :required="t.required"/>
                </td>
                <!-- Löschen eines Eintrags liegt immer vor -->
                <td>
                    <a href="#" v-if="!disable" class="btn text-danger btn-link" style="font-size: 1.2rem" @click.prevent="removeValue(v)">&#128465;</a>
                </td>
            </tr>
            </tbody>
        </table>
        <a href="#" v-if="!disable" class="float-right" @click.prevent="addValue()">{{ name }} hinzufügen</a>

        <!-- Der input, der die Daten beim Speichern an das Formular übermittelt -->
        <input type="hidden" :name="section_name" :disabled="!!disable" :form="form" v-model="encodedValues"/>
    </div>
</template>

<script>
export default {
    props: ["template", "val", "name", "section_name", "form", "disable"],

    data() {
        return {
            values: [],
            tpl: []
        };
    },

    mounted() {
        this.tpl = JSON.parse(this.template);
        this.values = JSON.parse(this.val);
    },

    computed: {
        encodedValues: function() {
            return JSON.stringify(this.values);
        },

        orderedValues: function () {
            return _.orderBy(this.values, 'number');
        }
    },

    methods: {
        /**
         * Füge einen Eintrag bzw. eine Tabellenzeile hinzu
         */
        addValue() {
            let v = {};
            this.tpl.forEach(function(t) {
                v[t.name] = t.def;
            })
            v.number = this.values.length;

            this.values.push(v);
        },

        /**
         * Verschiebe einen Eintrag bzw. eine Tabellenzeile
         *
         * @param e event
         * @param val zu verschiebende Zeile
         */
        changeNumber(e, val) {
            let old = val.number;
            let newVal = e.target.value;
            if (newVal >= 0 && newVal < this.values.length) {
                //nach unten verschieben
                if (newVal > old) {
                    this.values.forEach(v => {
                        if (v.number > old && v.number <= newVal) {
                            v.number--;
                        }
                    });
                }
                //nach oben verschieben
                if (newVal < old) {
                    this.values.forEach(v => {
                        if (v.number < old && v.number >= newVal) {
                            v.number++;
                        }
                    });
                }

                val.number = newVal;
            }
        },

        /**
         * Entferne einen Eintrag bzw. eine Tabellenzeile
         * 
         * @param v
         */
        removeValue(v) {
            let index;
            let number = v.number;

            this.values.forEach((val, i) => {
                if (val.number > number) {
                    val.number--;
                }
                if (Object.is(v, val)) {
                    index = i;
                }
            });

            this.values.splice(index, 1);
        }
    }
}
</script>
