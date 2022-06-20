<template>
                       <div class="form-group">
                     <label>{{label?label:''}}</label>
                        <input :value="value" :type="type"
                            :placeholder="placeholder?placeholder:''"
                            class="form-control"
                            :class="{ 'is-invalid': form.errors.has('value') }"
                             @input="update"
                            />
                        <has-error :form="form" :field="field"></has-error>
                    </div>
</template>

<script>
export default {
props:['value','type','label','form','placeholder','field'],
 methods: {
        update(event) {
            this.$emit('input', event.target.value)
        }
}}
</script>

<style>

</style>
