<!-- Komponente für eine Tabelle als Teil der Komponente section-text -->

<template>
    <div>
        <table class="table">
            <tr v-for="row in table.rows">
                <td v-for="col in row.cols">
                    <input class="form-control" type="text" v-model="col.text"/>
                </td>
                <td>
                    <a href="#" @click.prevent="addColumn(row)">Spalte hinzufügen</a>
                </td>
            </tr>
            <tr>
                <td>
                    <a href="#" @click.prevent="addRow()">Zeile hinzufügen</a>
                </td>
            </tr>
        </table>
    </div>
</template>
<script>
export default {
    props: ["val"],

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
