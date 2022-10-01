<template>
    <div class="col-md-12">
        <div class="row" v-if="$gate.isStudentOrParent()">
            <div class="col-md-12">
                <div>
                    <div class="card-header mb-2">
                        <div class="card-tittle">
                            <button
                                class="btn btn-success float-right m-2"
                                @click="newModal"
                            >
                                Upload PDF
                                <i
                                    class="fa fa-file-pdf"
                                    aria-hidden="true"
                                ></i>
                            </button>
                        </div>
                    </div>

                    <div class="col-md-12 row">
                        <div class="col-md-6">
                            <h4>{{ title }}</h4>
                            <div v-html="assignment_note"></div>

                            <div v-show="is_submited" class="mt-5">
                                <h4>
                                    Status: &nbsp;<span class="text-success">
                                        Submitted!</span
                                    >
                                </h4>
                                <h4 v-if="form.is_graded === 1">
                                    Score: &nbsp; {{ form.score }}%
                                </h4>
                                <h4 v-if="form.is_graded === 0">
                                    Score: Not graded
                                </h4>
                                <h4>Comment: &nbsp; {{ form.Tcomment }}</h4>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <h4>Paste/Do Assigmnent</h4>
                                <editor
                                    :options="editorOptions"
                                    height="500px"
                                    initialEditType="wysiwyg"
                                    previewStyle="vertical"
                                    ref="toastuiEditor"
                                    :initialValue="note"
                                    @change="getHtml"
                                />
                                <div class="card-footer">
                                    <button
                                        v-show="!is_submited"
                                        class="btn btn-success float-right"
                                        @click="createAssignment"
                                    >
                                        submit
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body table-responsive">
                        <table class="table table-hover">
                            <tbody>
                                <tr class="card-header">
                                    <th>S/N</th>
                                    <th>Comment</th>
                                    <th>Subject</th>
                                    <th>Level</th>
                                    <th>Arm</th>
                                    <th>Updated At</th>
                                    <th>Action</th>
                                </tr>

                                <tr
                                    v-for="(
                                        assignment, index
                                    ) in assignments.data"
                                    :key="index"
                                >
                                    <td>{{ index + 1 }}</td>
                                    <td>{{ assignment.comment }}</td>
                                    <td>
                                        {{
                                            assignment.subject
                                                ? assignment.subject.name
                                                : ""
                                        }}
                                    </td>
                                    <td>
                                        {{
                                            assignment.level
                                                ? assignment.level.level_name
                                                : ""
                                        }}
                                    </td>
                                    <td>
                                        {{
                                            assignment.arms
                                                ? assignment.arms.name
                                                : ""
                                        }}
                                    </td>
                                    <td>
                                        {{ assignment.created_at | myDate }}
                                    </td>

                                    <td class="row">
                                        <router-link
                                            v-show="!assignment.is_pdf"
                                            :to="`/assignment/${assignment.id}`"
                                            tag="a"
                                            target="_blank"
                                            >view/download</router-link
                                        >
                                        <router-link
                                            v-show="assignment.is_pdf"
                                            :to="`/pdfviewer/${assignment.id}`"
                                            tag="a"
                                            >view /</router-link
                                        >
                                        <a
                                            href="#"
                                            v-show="assignment.is_pdf"
                                            @click="downloadFile(assignment.id)"
                                            title="download"
                                            ><i class="fa fa-download"></i
                                        ></a>
                                        <a
                                            v-show="$gate.isStudentOrParent()"
                                            href="#"
                                            @click="getNote(assignment)"
                                        >
                                            <i fa fa-edit></i>
                                        </a>
                                        /
                                        <a
                                            href="#"
                                            v-show="$gate.isAdminOrTutor()"
                                            @click="
                                                deleteAssignment(assignment.id)
                                            "
                                            class="pl-2"
                                            title="delete"
                                        >
                                            <i class="fa fa-trash red"></i>
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <pagination
                            :data="assignments"
                            @pagination-change-page="getAssignment"
                        ></pagination>
                    </div>
                </div>
                <!-- /.card -->
            </div>
        </div>

        <div v-if="!$gate.isAdminOrTutorOrStudentOrParent()">
            <not-found></not-found>
        </div>

        <!-- Modal -->
        <div
            class="modal fade"
            id="addNewAssignment"
            tabindex="-1"
            role="dialog"
            aria-labelledby="addNewLabel"
            aria-hidden="true"
        >
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5
                            class="modal-title"
                            v-show="!editmode"
                            id="addNewLabel"
                        >
                            Add New
                        </h5>
                        <h5
                            class="modal-title"
                            v-show="editmode"
                            id="addNewLabel"
                        >
                            Update User's Info
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
                        @submit.prevent="
                            editmode ? updateAssignment() : createAssignment()
                        "
                        enctype="multipart/form-data"
                    >
                        <div class="modal-body">
                            <div class="form-group">
                                <input
                                    v-model="form.comment"
                                    type="text"
                                    name="name"
                                    class="form-control"
                                    placeholder="comment"
                                />
                            </div>

                            <div class="form-group">
                                <input
                                    type="file"
                                    ref="file"
                                    @change="setFile"
                                    class="form-control"
                                />
                            </div>
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
                                v-show="!editmode && !is_submited"
                                type="submit"
                                class="btn btn-primary"
                            >
                                Create
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { VueEditor } from "vue2-quill-editor";
import { Editor, plugin } from "@toast-ui/vue-editor";
import "tui-color-picker/dist/tui-color-picker.css";
import colorSyntax from "@toast-ui/editor-plugin-color-syntax";
import { Viewer } from "@toast-ui/vue-editor";
export default {
    components: {
        VueEditor,
        editor: Editor,
        viewer: Viewer,
    },
    data() {
        return {
            editmode: false,

            assignments: {},
            arms: {},
            levels: {},
            subjects: {},
            note: " ",
            editorText: "",
            title: "Assignment Title",
            assignment_note: "Select Assignment to submit",
            file: "",
            isEditor: false,
            is_submited: false,
            form: new Form({
                id: "",
                level_id: "",
                arm_id: "",
                note: "",
                file_name: "",
                score: "",
                Tcomment: "",
                is_graded: false,
            }),
            editorOptions: {
                minHeight: "500px",
                language: "en_US",
                useCommandShortcut: true,
                useDefaultHTMLSanitizer: true,
                usageStatistics: true,
                hideModeSwitch: true,
                plugins: [colorSyntax],
                toolbarItems: [
                    "heading",
                    "bold",
                    "italic",
                    "color",
                    "strike",
                    "divider",
                    "hr",
                    "quote",
                    "divider",
                    "ul",
                    "ol",
                    "task",
                    "indent",
                    "outdent",
                    "divider",
                    "table",
                    "image",
                    "link",
                    "divider",
                    "code",
                    "codeblock",
                    "underline",
                ],
            },
        };
    },
    mounted() {
        axios.get("/api/get_levels").then((res) => {
            this.levels = res.data;
        });
    },
    methods: {
        getAssignment(page = 1) {
            axios.get("/api/getAssignment?page=" + page).then((response) => {
                console.log(this.assignment.data);
                this.assignment = response.data;
            });
        },
        getNote(assignment) {
            this.form.is_graded = false;
            this.is_submited = false;
            axios
                .get("/api/student_assignment/" + assignment.id)
                .then((res) => {
                    if (res.data.is_submited === 1) {
                        this.is_submited = true;
                        this.assignment_note = assignment.note;
                        this.form.score = res.data.assignment.score;
                        this.form.Tcomment = res.data.assignment.comment;
                        this.form.is_graded = res.data.assignment.is_graded;
                        this.title = "Assignment Submitted";
                    } else {
                        this.assignment_note = assignment.note;
                        this.title = assignment.comment;
                        this.form.level_id = assignment.level_id;
                        this.form.arm_id = assignment.arm_id;
                        this.form.id = assignment.id;
                        this.is_submited = false;
                        this.title = "Assignment Not Submitted";
                    }
                });

            console.log(assignment.score);
        },
        updateAssignment() {
            this.$Progress.start();
            // console.log('Editing data');
            this.form
                .put("/api/assignment/" + this.form.id)
                .then(() => {
                    // success
                    $("#addNewAssignment").modal("hide");
                    swal.fire(
                        "Updated!",
                        "Information has been updated.",
                        "success"
                    );
                    this.$Progress.finish();
                    Fire.$emit("AfterCreate");
                })
                .catch(() => {
                    this.$Progress.fail();
                });
        },
        editModal(assigment) {
            this.editmode = true;
            this.form.reset();
            $("#addNewAssignment").modal("show");
            this.form.fill(user);
        },
        newModal() {
            this.editmode = false;
            //this.form.reset();
            this.isEditor = false;
            $("#addNewAssignment").modal("show");
        },
        deleteAssignment(id) {
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
                    this.form
                        .delete("/api/assignment/" + id)
                        .then(() => {
                            swal.fire(
                                "Deleted!",
                                "Your file has been deleted.",
                                "success"
                            );
                            Fire.$emit("AfterCreate");
                        })
                        .catch(() => {
                            swal.fire(
                                "Failed!",
                                "There was something wronge.",
                                "warning"
                            );
                        });
                }
            });
        },
        setEditor() {
            this.isEditor = true;
        },
        loadSubjects() {
            if (this.$gate.isAdminOrTutor()) {
                axios.get("/api/load_list").then((res) => {
                    this.subjects = res.data;
                    console.log(this.subjects);
                });
            }
        },
        getHtml() {
            let html = this.$refs.toastuiEditor.invoke("getHtml");
            this.note = html;
        },
        createAssignment() {
            this.$Progress.start();
            const formData = new FormData();
            formData.append("file", this.file);
            formData.append("note", this.note);
            formData.append("level_id", this.form.level_id);
            formData.append("arm_id", this.form.arm_id);
            formData.append("id", this.form.id);

            // console.log(formData);
            axios
                .post("/api/student_assignment", formData)
                .then((res) => {
                    $("#addNewAssignment").modal("hide");
                    toast.fire({
                        type: "success",
                        title: "Assignment successfully uploaded",
                    });
                    console.log(res.data);
                    this.$Progress.finish();
                    //  this.note='select another Assignment'
                    Fire.$emit("AfterCreate");
                })
                .catch((err) => {
                    toast.fire({
                        type: "failure",
                        title: "there was error uploading the file" + err,
                    });
                    console.log(err);
                });
        },
        setFile() {
            this.file = this.$refs.file.files[0];
            console.log(this.file);
        },
        loadAssignment() {
            axios.get("/api/getAssignment").then((res) => {
                this.assignments = res.data;
            });
        },

        downloadFile(id) {
            axios
                .get("/api/assignment_view/" + id, {
                    responseType: "arraybuffer",
                })
                .then((response) => {
                    let blob = new Blob([response.data], {
                        type: "application/pdf",
                    });
                    let link = document.createElement("a");
                    link.href = window.URL.createObjectURL(blob);
                    link.download = "assignment.pdf";
                    link.click();
                });
        },
    },

    created() {
        Fire.$on("searching", () => {
            let query = this.$parent.search;
            axios
                .get("/api/findAssignment?q=" + query)
                .then((data) => {
                    this.assignments = data.data;
                })
                .catch(() => {});
        });
        this.loadAssignment();
        Fire.$on("AfterCreate", () => {
            this.loadAssignment();
        });
        $("#addNewAssignment").modal("hide");
        //    setInterval(() => this.loadUsers(), 3000);
    },
};
</script>
