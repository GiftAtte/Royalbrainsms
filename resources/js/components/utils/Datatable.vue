<template>
    <div class="card card-outline card-navy">
        <div class="card-header">
            <div class="row">
                <div class="col-md-6">
                    <h3 class="card-title">
                        {{ title.toUpperCase() }}
                    </h3>
                </div>
                <div class="col-md-6 col-sm-6">
                    <slot name="addButton">
                        <button
                            @click="newModal"
                            class="btn btn-primary float-right"
                        >
                            Add New <i class="fas fa-user-plus fa-fw"></i>
                        </button>
                    </slot>
                </div>
            </div>
            <div class="pt-3 row">
                <div class="col-md-6">
                    <button
                        title="cart. click to show content"
                        @click="goToCart"
                        type="button"
                        class="btn btn-primary position-relative"
                    >
                        <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                        <span
                            v-show="getProducts().length"
                            class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                            >{{ getProducts().length }}</span
                        >
                    </button>
                    <h3 v-show="!tbData && !appData">loading....</h3>
                </div>
                <div class="col-md-6 search-container">
                    <slot name="search">
                        <input
                            class="form-control float-right"
                            v-model="queryString"
                            type="text"
                            placeholder="Search...."
                        />

                        <i class="fa fa-search"></i>
                    </slot>
                </div>
            </div>
        </div>
        <div class="card-body table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>S/N</th>
                        <th v-for="header in headers" :key="header.key">
                            {{ header.header }}
                        </th>
                        <slot name="extra-action"> </slot>
                        <th v-if="action">Modify</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="(row, index) in queryString.length
                            ? tbData
                            : appData"
                        :key="row.id"
                    >
                        <td colspan="0.5">{{ index + 1 }}</td>
                        <td v-for="header in headers" :key="header.key">
                            {{ row[header.key] }}
                        </td>
                        <slot name="extra-action-body" :row="row"> </slot>
                        <td v-if="action">
                            <a href="#" @click="editModal(row)">
                                <i class="fa fa-edit blue"></i>
                            </a>
                            &nbsp;|&nbsp;
                            <a href="#" @click="deleteItem(row.id)">
                                <i class="fa fa-trash red"></i>
                            </a>
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    <slot name="extra-row" :data="tbData"></slot>
                </tfoot>
            </table>
        </div>
        <div class="card-footer">
            <slot name="card-footer"></slot>
        </div>
        <!-- Modal -->

        <app-modal
            :editmode="editmode"
            :updateAction="updateAction"
            :createAction="createAction"
            :updateTitle="updateTitle"
            :modalTitle="modalTitle"
            :sumitButtonText="sumitButtonText"
            :modalSize="modalSize"
        >
            <slot name="modal-fields"></slot>
        </app-modal>

        <!-- End of Modal -->
    </div>
</template>

<script>
import { mapState, mapGetters } from "vuex";
import Templates from "../results/Templates.vue";
export default {
    components: { Templates },
    props: [
        "headers",
        "data",
        "deleteAction",
        "createAction",
        "updateAction",
        "search",
        "title",
        "updateTitle",
        "action",
        "form",
        "modalId",
        "createButton",
        "modalTitle",
        "sumitButtonText",
        "modalSize",
    ],
    computed: {
        ...mapState(["productCount"]),
        appData() {
            return this.data;
        },
    },
    data() {
        return {
            tbData: [],
            queryString: "",
            editmode: false,
            isData: false,
        };
    },
    created() {
        this.searchItem();
    },

    methods: {
        ...mapGetters(["getCount", "getProducts"]),
        searchItem() {
            if (this.queryString) {
                let queryData = this.data.filter((item) => {
                    console.log(item);
                    //innerQuery += Object.values(item).join('');
                    return Object.values(item)
                        .join("")
                        .toLowerCase()
                        .includes(this.queryString.toLowerCase());
                });
                this.tbData = queryData;
            } else {
                this.tbData = this.data;
            }
        },
        editModal(data) {
            this.form.fill(data);
            $("#appModal").modal("show");
            this.editmode = true;
        },
        newModal() {
            $("#appModal").modal("show");
            this.editmode = false;
            this.form.reset();
        },

        deleteItem(id) {
            swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!",
            }).then((result) => {
                // Send request to the server
                if (result.value) {
                    this.deleteAction(id);
                    swal.fire("Deleted!", "Item has been deleted.", "success");
                }
            });
        },
        goToCart() {
            this.$router.push("/inventory/cart");
        },
    },

    watch: {
        queryString(newQueryString, oldQueryString) {
            if (newQueryString.length) {
                this.searchItem();
            } else {
                this.tbData = this.data;
            }
        },
    },
};
</script>
<style scoped>
.search-container {
    display: flex;
    justify-items: flex-end;
    align-items: center;
}
.search-input {
    width: 70%;
    padding: 0.8rem;
    border-radius: 20px;
    float: right;
}

.fa-search {
    position: relative;
    margin-left: -1.5rem;
    color: gray;
}
</style>
