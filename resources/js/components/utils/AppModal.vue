<template>
    <div
        class="modal fade"
        id="appModal"
        tabindex="-1"
        role="dialog"
        aria-labelledby="exampleModalLabel"
        aria-hidden="true"
    >
        <div :class="`modal-dialog modal-dialog-centered ${modalSize}`">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" v-show="!editmode" id="addNewLabel">
                        {{ modalTitle ? modalTitle : "Add New" }}
                    </h5>
                    <h5 class="modal-title" v-show="editmode" id="addNewLabel">
                        {{ updateTitle }}
                    </h5>
                    <button
                        type="button"
                        class="close"
                        data-dismiss="modal"
                        aria-label="Close"
                    >
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form
                    @submit.prevent="editmode ? updateAction() : createAction()"
                >
                    <div class="modal-body">
                        <slot></slot>
                    </div>

                    <div class="modal-footer">
                        <button
                            type="button"
                            class="btn btn-danger"
                            data-dismiss="modal"
                        >
                            Close
                        </button>
                        <button
                            v-show="editmode"
                            type="submit"
                            class="btn btn-success"
                        >
                            Update
                        </button>
                        <button
                            v-if="!hideCreateBtn"
                            id="createButton"
                            v-show="!editmode"
                            type="submit"
                            class="btn btn-primary"
                        >
                            {{ sumitButtonText ? sumitButtonText : "Create" }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: [
        "editmode",
        "updateAction",
        "createAction",
        "updateTitle",
        "modalTitle",
        "sumitButtonText",
        "modalSize",
        "hideCreateBtn",
    ],

    //inject: ["name"],
};
</script>
