<template>
    <div class="rating-stars" :class="id"></div>
</template>

<script>
require("./../star-rating");

export default {
    props: ["stars"],

    data() {
        return {
            id: null,
            rating: 0,
        }
    },

    created() {
        this.id = "star-rating-" + this._uid;
    },

    mounted() {
        $("." + this.id).starRating({
            totalStars: 5,
            initialRating: this.stars,
            starSize: 18,
            strokeWidth: 0,
            useGradient: false,
            forceRoundUp: true,
            disableAfterRate: false,
            callback: (rating) => {
                this.rating = rating;
                this.$emit("updaterating", this.rating);
            },
        });
    }
}
</script>
