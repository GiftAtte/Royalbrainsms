<template>
    <div class="form-group">
        <label>{{ label }}</label>
        <select
            class="form-control"
            :name="name"
            :id="id"
            @change="onChange ? onChange : fallback"
            :value="value"
            @input="update"
            :class="{ 'is-invalid': form.errors.has(field) }"
        >
            <option value="" selected>{{ placeholder }}</option>
            <option
                v-for="option in options"
                :value="option[optionValue]"
                :key="option.id"
            >
                {{ option[optionLabel] }}
            </option>
        </select>
        <has-error :form="form" :field="field"></has-error>
    </div>
</template>

<script>
export default {
    props: [
        "label",
        "form",
        "placeholder",
        "field",
        "options",
        "onChange",
        "value",
        "id",
        "name",
        "optionValue",
        "optionLabel",
    ],
    data() {
        return {
            data: [],
        };
    },
    methods: {
        fallback() {},
        update(event) {
            this.$emit("input", event.target.value);
        },
    },
    created() {
        this.options.forEach((option) => {
            this.data.push({
                id: option.id,
                label: option[this.optionLabel],
                value: option[this.optionValue],
            });
        });
    },
};
</script>
