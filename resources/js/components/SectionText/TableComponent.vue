<!-- Komponente für eine Tabelle als Teil der Komponente section-text -->

<template>
    <div>
        <table class="table">
            <tr v-for="(row, i) in table.rows">
                <td class="flex-column">
                    <div>
                        <input :disabled="!!disable" type="checkbox" :id="name + number + 'table_is_header_' + i" v-model="row.is_header"/>
                        <label :for="name + number + 'table_is_header_' + i">Kopfzeile</label>
                    </div>
                    <a v-if="!disable" href="#" @click.prevent="removeRow(i)" class="text-danger">Zeile entfernen</a>
                </td>
                <td v-for="(col, j) in row.cols" class="flex-column">
                    <input :disabled="!!disable" class="form-control" type="text" v-model="col.text"/>
                    <a v-if="!disable" href="#" @click.prevent="removeColumn(row, j)" class="text-danger">Spalte entfernen</a>
                </td>
                <td v-if="!disable">
                    <a href="#" @click.prevent="addColumn(row)">Spalte hinzufügen</a>
                </td>
            </tr>
            <tr v-if="!disable">
                <td>
                    <a href="#" @click.prevent="addRow()">Zeile hinzufügen</a>
                </td>
            </tr>
        </table>
    </div>
</template>
<script>
export default {
    props: ["val", "name", "number", "disable"],

    data() {
        return {
            table: {}
        };
    },

    mounted() {
        this.table = this.val;
    },

    methods: {
        addColumn(row) {
            row.cols.push({text: ""});
        },

        removeColumn(row, i) {
            row.cols.splice(i, 1);
        },

        addRow() {
            this.table.rows.push({
                is_header: false,
                cols: []
            });
        },

        removeRow(i) {
            this.table.rows.splice(i, 1);
        }
    }
};
</script>
