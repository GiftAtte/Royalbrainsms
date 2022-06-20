<template>
    <div class="form-group">
        <label>{{ label }}</label>
        <select
        class="form-control"
        :name="name"
        :id="id"
        @change="onChange"
        :value="value"
        @input="event => { $emit('input', event.target.value) }"
      >
      <option v-for="option in options" :value="option.value" :key="option.id">
      {{ option.label}}</option>
    </select>
      </div>
</template>

<script>
export default {
    props: ['label','form','placeholder','field','options', 'onChange', 'value', 'id', 'name']
}
</script>
