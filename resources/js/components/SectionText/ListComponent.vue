<!-- Komponente für eine Liste als Teil der Komponente section-text -->

<template>
    <ul v-if="list.order==='unordered'">
        <li v-for="(item, i) in list.items" class="my-1">
            <div class="d-flex justify-content-between">
                <input :disabled="!!disable" type="text" class="form-control mr-2" v-model="item.text"/>
                <a v-if="!disable" href="#" class="text-danger" @click.prevent="removeItem(i)">Entfernen</a>
            </div>
        </li>
        <li v-if="!disable">
            <a href="#" @click.prevent="addItem()">Listenelement hinzufügen</a>
        </li>
    </ul>
    <ol v-else :type="list.order">
        <li v-for="(item, i) in list.items">
            <div class="d-flex justify-content-between">
                <input :disabled="!!disable" type="text" class="form-control mr-2" v-model="item.text"/>
                <a v-if="!disable" href="#" class="text-danger" @click.prevent="removeItem(i)">Entfernen</a>
            </div>
        </li>
        <li v-if="!disable">
            <a href="#" @click.prevent="addItem()">Listenelement hinzufügen</a>
        </li>
    </ol>
</template>
<script>
export default {
    props: ["val", "name", "number", "disable"],

    data() {
        return {
            list: {}
        };
    },

    mounted() {
        this.list = this.val;
    },

    methods: {
        addItem() {
            this.list.items.push({text: ""});
        },

        removeItem(i) {
            this.list.items.splice(i, 1);
        }
    }
};
</script>
