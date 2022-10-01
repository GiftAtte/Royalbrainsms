<template>
    <div class="container row">
        <div
            class="small-box bg-primary birthday col-md-5 text-center container"
            v-for="student in data"
            :key="student.id"
        >
            <div class="inner">
                <img
                    class="img-circle"
                    :src="`/img/profile/stud${student.id}.png`"
                    width="50"
                    height="50"
                    alt="profile"
                    onerror="this.src='/img/profile.png'"
                />
            </div>
            <div class="icon">
                <i class="ion ion-bag"></i>
            </div>
            <h5>{{ student.surname }} &nbsp; {{ student.first_name }}</h5>
            <a href="/users" class="small-box-footer"
                >{{ student.dob | myDate }}
            </a>
        </div>
    </div>
</template>

<script>
export default {
    props: ["data"],
    methods: {
        getImage(id) {},
    },
    created() {
        $(".carousel").carousel();
    },
};
</script>

<style></style>
