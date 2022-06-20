<template>
<div class="card">
    <div class="row header-container">
     <div class="col-md-6">
        <h3 class="card-title text-bold">
   {{ title }}
        </h3>

     </div>
     <div class="col-md-6 row">
        <div class="col-md-4"></div>
        <div class="col-md-8 search-container ">
               <input
                      class=" form-control"
                        v-model="form.querryString"
                        type="text"
                        :form="form"
                        placeholder="Search...."
                    >
                    <i class="fa fa-search"></i>
        </div>


     </div>
    </div>
<div class="card-body table-responsive">
<table class="table">
    <thead>
        <tr>
            <th v-for="header in headers" :key="header.key">
            {{ header.header }}
            </th>
            <slot name="extra-headers"></slot>
        </tr>
    </thead>
<tbody>
    <tr v-for="row in data" :key="row.id">
     <td v-for="header in headers" :key="header.key">
       {{ row[header.key] }}
     </td>
     <slot name="extra-column"></slot>
    </tr>

</tbody>

</table>
</div>
</div>
</template>

<script>
export default {

    props: [
        'headers',
        'data',
        'deleteAction',
        'updateAction',
        'search',
        'title',
    ],
    data() {
        return {
            querryString: '',
            form: new Form({

            })
        }
    },
    search(querryString) {

    }

}
</script>
<style>
.search-container{
    display: flex;
    justify-items: flex-end;
    align-items: center;
    right: 1rem;
}
.search-input{
    width: 70%;
     padding: 0.8rem;
     border-radius: 20px;
     float: right;

}
.header-container{
    display: flex;
    justify-items: flex-end;
    align-items: center;
    margin: 1.5rem;

}
.fa-search{
    position: relative;
    margin-left: -1.5rem;
    color: gray;
}
</style>
