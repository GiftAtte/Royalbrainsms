<template>
    <div class="row text-bold">
        GRADING KEY
        <hr />
        <p class="text-bold">
            |<span v-for="grade in grades" :Key="grade.id">
                &nbsp;{{ grade.lower_bound }}&nbsp;-&nbsp;
                {{ grade.upper_bound }}&nbsp;:&nbsp;{{ grade.grade }} &nbsp;:
                &nbsp;{{ grade.narration }}&nbsp;|&nbsp;
            </span>
        </p>
        <hr />
    </div>
</template>

<script>
export default {
    props: ["grades"],
};
</script>
