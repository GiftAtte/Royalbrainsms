<template>
    <div class="birthday small-box card-outline card-navy">
        <div class="inner">
            <div class="col-md-12">
                <h5 class="text-uppercase text-center text-primary">
                    {{ news.title }}
                </h5>
                <hr />
                <img
                    height="50"
                    width="50"
                    :src="'img/schools/' + school.logo"
                    alt=""
                    class="img-rounded float-right"
                />
            </div>

            <p>
                {{ news.content }}
            </p>
            <p>
                <img
                    :src="`img/post/${news.id}.png`"
                    alt=""
                    class="img-rounded float-right"
                    onerror="this.style.display:'none'"
                    width="100%"
                />
            </p>
        </div>
        <div class="icon">
            <i class="ion ion-bag"></i>
        </div>

        <div class="card-footer text-primary">
            <button class="btn btn-flat text-primary">
                <i class="fa fa-calendar"></i>
                &nbsp; {{ news.created_at | myDate }}
            </button>
            <i class="fa fa-comment float-right"></i>
        </div>
    </div>
</template>

<script>
export default {
    props: ["school", "news"],
};
</script>

<style scoped>
news-feed {
    box-shadow: 5px;
}
</style>
>
