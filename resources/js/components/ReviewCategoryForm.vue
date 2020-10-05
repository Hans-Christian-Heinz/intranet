<template>
    <div class="form-group">
        <div class="d-flex justify-content-between mb-2">
            <h6 class="my-auto"><strong>{{ category.name }}</strong></h6>

            <star-rating class="my-auto" :stars="stars" @updaterating="this.updateRating"></star-rating>
            <input type="hidden" :name="'category-' + category.id + '-stars'" :value="this.rating">
        </div>

        <textarea class="form-control" :class="{ 'd-none': rating == 0 }" :name="'category-' + category.id + '-comment'" rows="1" placeholder="Kommentar" v-text="this.comment"></textarea>
    </div>
</template>

<script>
export default {
    props: ["category", "comment", "stars"],

    data() {
        return {
            rating: 0
        };
    },

    mounted() {
        this.rating = this.stars;
    },

    methods: {
        updateRating(rating) {
            this.rating = rating;
        }
    }
}
</script>
