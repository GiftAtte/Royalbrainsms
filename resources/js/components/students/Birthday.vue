<template>
    <div class="col-md-12 col-xs-12">
        <!-- small box -->
        <div class="small-box bg-primary birthday">
            <div class="inner">
                <h5 class="text-uppercase text-center">
                    <slot name="title"> Today's Birthday Celebrants </slot>
                </h5>

                <carousel :data="students" v-if="students.length" />
                <h5 v-else>No Celebrant Today!</h5>
            </div>
            <div class="icon">
                <i class="ion ion-bag"></i>
            </div>
            <slot name="link">
                <router-link to="/birthdays" class="small-box-footer"
                    >This Month's Celebrants
                    <i class="fas fa-arrow-circle-right"></i
                ></router-link>
            </slot>
        </div>
    </div>
</template>

<script>
import Carousel from "./Carousel.vue";
export default {
    components: { Carousel },
    created() {
        axios.get("/api/birthday").then((res) => {
            this.students = res.data;
            console.log("Birthday", res.data);
        });
    },
    data() {
        return {
            students: [],
        };
    },
};
</script>

<style>
.small-box {
    border-radius: 10px;
}
</style>
