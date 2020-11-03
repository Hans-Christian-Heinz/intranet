<!-- Komponente für einen Link als Teil der Komponente section-text -->

<template>
    <div class="d-flex justify-content-between">
        <select class="form-control mr-5" v-model="link.target">
            <!-- todo options -->
        </select>
        <input type="text" class="form-control ml-5" v-model="link.text"/>
    </div>
</template>
<script>
export default {
    props: ["val"],

    data() {
        return {
            link: {}
        };
    },

    mounted() {
        this.link = this.val;
    }
};
</script>
