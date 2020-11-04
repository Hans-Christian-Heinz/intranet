<!-- Komponente für einen Link als Teil der Komponente section-text -->

<template>
    <div class="d-flex justify-content-between">
        <div class="flex-column" style="width: 45%">
            <label :for="name + number + 'LinkTarget'" class="mb-1">Zielabschnitt des Links</label>
            <select :disabled="!!disable" class="form-control" :id="name + number + 'LinkTarget'" v-model="link.target" @change="chooseTarget($event)">
                <option v-for="s in sections" :id="name + number + 'Option' + s.name" :value="s.name">{{ s.heading }}</option>
            </select>
        </div>
        <div class="flex-column" style="width: 45%">
            <label :for="name + number + 'LinkText'" class="mb-1">Text des Links</label>
            <input :disabled="!!disable" :id="name + number + 'LinkText'" type="text" class="form-control" v-model="link.text"/>
        </div>
    </div>
</template>
<script>
export default {
    props: ["val", "available_sections", "name", "number", "disable"],

    data() {
        return {
            link: {},
            sections: []
        };
    },

    mounted() {
        this.link = this.val;
        this.sections = JSON.parse(this.available_sections);
        if (! this.link.target) {
            this.link.target = this.sections[0].name;
            this.link.text = this.sections[0].heading;
        }
    },

    methods: {
        chooseTarget(e) {
            this.link.text = document.getElementById(this.name + this.number + 'Option' + e.target.value).innerText;
        }
    }
};
</script>
