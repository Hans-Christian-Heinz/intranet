<!-- Teil des Formulars für Projektanträge und Dokumentationen: Inputs für Abschnitte, die als Tabellen dargestellt werden -->

<template>
    <div>
        <table class="table border-0">
            <thead>
            <tr>
                <th v-if="!disable">Drag-<br/>Drop</th>
                <th>Reihenfolge</th>
                <th v-for="t in tpl">{{ t.name }}</th>
                <th>Löschen</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="v in orderedValues">
                <!-- Reihenfolge der Einträge liegt immer vor -->
                <td v-if="!disable" class="bg-secondary draggable" :data-order="v.number" v-on:mousedown="drag_mousedown($event, v)"></td>
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
            tpl: [],
            dragging: {}
        };
    },

    mounted() {
        // this.tpl = JSON.parse(this.template);
        // this.values = JSON.parse(this.val);
        this.tpl = this.parse_json(this.template);
        this.values = this.parse_json(this.val);
    },

    created() {
        const throttledMouseMove = _.throttle(this.drag_mousemove, 50);
        window.addEventListener('mousemove', throttledMouseMove);
        window.addEventListener("mouseup", this.drag_mouseup);
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

            console.log(this.val)
            console.log(this.values);

            this.values.push(v);
        },

        /**
         * Verschiebe einen Eintrag bzw. eine Tabellenzeile
         *
         * @param e event
         * @param val zu verschiebende Zeile
         */
        changeNumber(e, val) {
            this.changeNumberHelp(e.target.value, val);
        },

        changeNumberHelp(newNumber, val) {
            let old = val.number;
            if (newNumber >= 0 && newNumber < this.values.length) {
                //nach unten verschieben
                if (newNumber > old) {
                    this.values.forEach(v => {
                        if (v.number > old && v.number <= newNumber) {
                            v.number--;
                        }
                    });
                }
                //nach oben verschieben
                if (newNumber < old) {
                    this.values.forEach(v => {
                        if (v.number < old && v.number >= newNumber) {
                            v.number++;
                        }
                    });
                }

                val.number = newNumber;
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
        },

        parse_json(val) {
            if(val)
                return JSON.parse(val);
            else
                return [];
        },

        drag_mousedown(e, val) {
            //left mouse button
            if(e.button === 0) {
                e.preventDefault();
                document.body.style.cursor = "grabbing";
                this.dragging = val;

                // this.moveCopy = Object.assign(e.target.parentNode);
                let moveCopy = e.target.parentNode.cloneNode(true);
                moveCopy.style.position = "absolute";
                moveCopy.style.pointerEvents = "none";
                moveCopy.setAttribute("id", "moveCopy");
                document.body.appendChild(moveCopy);
            }
        },

        drag_mouseup(e) {
            //left mouse button
            if(e.button === 0) {
                e.preventDefault();
                document.body.style.cursor = "auto";

                let path = e.path || (e.composedPath && e.composedPath());
                if(! _.isEmpty(this.dragging) && path.reduce((acc, current) => {
                    return acc || (current.classList && current.classList.contains("draggable"));
                }, false)) {
                    let dragTarget = path.find(element => element.classList.contains("draggable"));
                    let order = dragTarget.dataset.order;

                    this.changeNumberHelp(order, this.dragging);
                }

                this.dragging = {};
                let moveCopy = document.getElementById("moveCopy");
                if(moveCopy)
                    moveCopy.remove();
            }
        },

        drag_mousemove(e) {
            if(! _.isEmpty(this.dragging)) {
                let moveCopy = document.getElementById("moveCopy");
                moveCopy.style.top = e.y + "px";
                moveCopy.style.left = e.x + "px";
            }
        }
    }
}
</script>
