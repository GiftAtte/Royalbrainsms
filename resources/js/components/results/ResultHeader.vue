<template>
    <div class="card-body row col-md-12 pt-1 mt-3">
        <div class="col-md-4 col-sm-3">
            <img
                :src="`/img/schools/${school.logo}`"
                class="result-logo float-right"
                alt="logo"
                width="50%"
            />
        </div>

        <div class="col-md-8 col-sm-9">
            <h3 class="text-primary text-uppercase">{{ school.name }},</h3>
            <h5>
                <i class="fa fa-home"></i>:&nbsp;
                {{ school.contact_address }},&nbsp;
                {{ school.state }}
            </h5>
            <h5>
                <i class="fa fa-phone"></i>:&nbsp; {{ school.phone }}. &nbsp;
                <i class="fa fa-envelope"></i>:
                {{ school.email }}
            </h5>
            <h5 class="text-red">
                <i class="fa fa-globe"></i>:&nbsp; {{ school.website }}.
            </h5>
        </div>
    </div>
</template>

<script>
export default {
    props: ["school", "report"],
};
</script>
