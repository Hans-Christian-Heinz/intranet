<template>
    <form :action="route" method="POST">
        <review-category-form v-for="category in categories" :category="category" :key="category.id"></review-category-form>


        <div class="form-group mt-5">
            <label for="comment">Zusätzlicher Kommentar</label>
            <textarea class="form-control" name="comment" id="comment" rows="3" placeholder="Kommentar"></textarea>
        </div>

        <div class="form-group mb-0">
            <button type="submit" class="btn btn-primary">Bewertung abgeben</button>
        </div>
    </form>
</template>

<script>
export default {
    props: ["categories", "route"],

    data() {
        return {
            form: {},
        };
    },

    methods: {
        submit() {
            
        }
    }
}
</script>
