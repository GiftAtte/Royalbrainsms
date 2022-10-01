<template>
    <div>
        <div class="content-header">
            <div class="container-fluid navy">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Reports</h1>
                    </div>
                    <!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">
                                <a href="#">Home</a>
                            </li>
                            <li class="breadcrumb-item active">Report Group</li>
                        </ol>
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </div>

        <div class="content" v-if="$gate.isAdminOrTutorOrStudentOrParent()">
            <div class="col-12">
                <div class="card card-navy card-outline">
                    <div class="card-header">
                        <div class="row float-right">
                            <div class="card-tools">
                                <button
                                    v-show="$gate.isAdmin()"
                                    class="btn btn-primary btn-md float-right m-2"
                                    @click="newModal"
                                >
                                    <i class="fa fa-plus-circle"></i>
                                    Add New
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive">
                        <table class="table table-hover" id="data_tb">
                            <tbody>
                                <tr>
                                    <th>R/ID</th>
                                    <th>Title</th>

                                    <th>Class/Level</th>
                                    <th>Action</th>
                                    <th v-show="$gate.isAdminOrTutor()">
                                        Modify
                                    </th>
                                </tr>

                                <tr
                                    v-for="report in reports.data"
                                    :key="report.id"
                                >
                                    <td>{{ report.id }}</td>
                                    <td>{{ report.title }}</td>
                                    <td>
                                        {{
                                            report.levels
                                                ? report.levels.level_name
                                                : ""
                                        }}
                                    </td>

                                    <td
                                        style="
                                            display: flex;
                                            flex-direction: row;
                                        "
                                    >
                                        <router-link
                                            class="btn btn-flat"
                                            v-show="$gate.isAdminOrTutor()"
                                            :to="`result_list/${report.id}`"
                                            title="view report list"
                                            tag="button"
                                            exact
                                            ><i class="fa fa-eye"></i
                                        ></router-link>
                                        <a
                                            class="btn btn-flat"
                                            v-show="$gate.isAdminOrTutor()"
                                            href="#"
                                            title="view master sheet"
                                            tag="a"
                                            exact
                                            @click="loadMasterArms(report.id)"
                                            ><i class="fa fa-book"></i
                                        ></a>
                                        <a
                                            class="btn btn-flat btn-sm"
                                            v-show="$gate.isAdmin()"
                                            disabled="true"
                                            href="#"
                                            @click="download(report.id)"
                                        >
                                            <i
                                                class="fa fa-download"
                                                title="Go To Downloads"
                                            ></i>
                                        </a>
                                        <button
                                            title="Compute Summary"
                                            v-show="$gate.isAdminOrTutor()"
                                            type="btn"
                                            class="btn btn-flat btn-sm mr-2"
                                            @click="loadArms(report.id)"
                                        >
                                            <i
                                                class="fas fa-calculator"
                                                aria-hidden="true"
                                            ></i>
                                        </button>
                                        <toggle-button
                                            @change="activateReport(report.id)"
                                            v-show="$gate.isAdmin()"
                                            :label="true"
                                            :labels="{
                                                checked: 'ON',
                                                unchecked: 'OFF',
                                            }"
                                            :height="20"
                                            :font-size="14"
                                            :value="report.is_ready"
                                            :color="'navy'"
                                            :name="'activated'"
                                            class="pl-2"
                                        />
                                    </td>
                                    <td class="">
                                        <a
                                            href="#"
                                            @click="editModal(report)"
                                            v-show="$gate.isAdmin()"
                                            class="pl-2"
                                        >
                                            <i class="fa fa-edit blue"></i>
                                        </a>

                                        <a
                                            href="#"
                                            @click="deleteReport(report.id)"
                                            v-show="$gate.isAdmin()"
                                            class="pl-2"
                                        >
                                            <i class="fa fa-trash red"></i>
                                        </a>
                                    </td>

                                    <td
                                        class="row"
                                        v-show="$gate.isStudentOrParent()"
                                    >
                                        <p v-if="report.is_ready > 0">
                                            <a
                                                class="btn btn-primary mr-2"
                                                v-show="
                                                    $gate.isStudentOrParent()
                                                "
                                                disabled="true"
                                                href="#"
                                                @click="download(report.id)"
                                                ><i
                                                    class="fa fa-download"
                                                    title="Go To Downloads"
                                                ></i
                                            ></a>
                                            <router-link
                                                class="btn btn-success btn-sm"
                                                v-show="
                                                    report.activation_status
                                                "
                                                :to="`result/${report.id}/${isStudent}`"
                                                title="view report card"
                                                tag="a"
                                                exact
                                                ><i class="fa fa-eye"></i>
                                                &nbsp;View Reult</router-link
                                            >
                                        </p>
                                        <button
                                            v-else
                                            disabled
                                            class="btn btn-danger btn-sm"
                                            v-show="report.activation_status"
                                            href="#`"
                                            title="Result not ready"
                                        >
                                            <i class="fa fa-eye"></i> &nbsp; Not
                                            ready
                                        </button>
                                        <button
                                            @click="
                                                showActivationModal(report.id)
                                            "
                                            v-show="!report.activation_status"
                                            class="btn btn-sm btn-danger"
                                            :title="
                                                report.is_ready
                                                    ? 'enter activation code'
                                                    : 'result not ready'
                                            "
                                        >
                                            Activation Key?
                                        </button>

                                        <router-link
                                            v-if="
                                                report.term_id === 3 &&
                                                report.activation_status &&
                                                report.type === 'terminal'
                                            "
                                            :to="`/result/${report.id}/${isStudent}/annual`"
                                            tag="a"
                                            exact
                                            class="btn btn-primary ml-2 btn-sm"
                                            >view Annual Report</router-link
                                        >
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <pagination
                            :data="reports"
                            @pagination-change-page="getResults"
                        ></pagination>
                    </div>
                </div>
                <!-- /.card -->
            </div>
        </div>

        <div v-if="!$gate.isAdminOrTutorOrStudentOrParent()">
            <not-found></not-found>
        </div>
        <!-- Arms Modal -->
        <loading
            :active.sync="isLoading"
            :can-cancel="false"
            :on-cancel="onCancel"
            color="blue"
            :is-full-page="fullPage"
        ></loading>
        <div
            class="modal fade modal"
            id="addNew"
            tabindex="-1"
            role="dialog"
            aria-labelledby="addNewLabel"
            aria-hidden="true"
        >
            <div
                class="modal-dialog modal-dialog-centered modal-lg"
                role="document"
            >
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
                            Update Report's Info
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
                            editmode ? updateReport() : CreateReport()
                        "
                    >
                        <div class="modal-body">
                            <div class="row col-md-12">
                                <div class="col-md-6">
                                    <input-field
                                        v-model="form.title"
                                        type="text"
                                        name="title"
                                        placeholder="Report Title eg 2018/2019-JSS1-FIRST_TERM"
                                        label="Report Title"
                                        :form="form"
                                    />

                                    <select-input
                                        :form="form"
                                        :field="resultTemplate"
                                        label="Result Template"
                                        :options="schoolTemplates"
                                        id="type"
                                        name="type"
                                        optionValue="name"
                                        optionLabel="name"
                                        placeholder="Select Template"
                                        v-model="form.type"
                                    />
                                    <select-input
                                        :form="form"
                                        :field="level_id"
                                        label="Level"
                                        :options="levels"
                                        id="level_id"
                                        name="level_id"
                                        optionValue="id"
                                        optionLabel="level_name"
                                        placeholder="Select level"
                                        v-model="form.level_id"
                                    />

                                    <select-input
                                        :form="form"
                                        :field="session"
                                        label="Session"
                                        :options="sessions"
                                        id="session_id"
                                        name="session_id"
                                        optionValue="id"
                                        optionLabel="name"
                                        placeholder="Select Session"
                                        v-model="form.session_id"
                                    />

                                    <select-input
                                        :form="form"
                                        :field="term_id"
                                        label="Term"
                                        :options="terms"
                                        id="term_id"
                                        name="term_id"
                                        optionValue="id"
                                        optionLabel="name"
                                        placeholder="Select term"
                                        v-model="form.term_id"
                                    />
                                </div>

                                <div class="col-md-6">
                                    <input-field
                                        label="Term Start"
                                        name="term_start"
                                        v-model="form.term_start"
                                        id="term_start"
                                        :form="form"
                                        field="term_start"
                                        type="date"
                                    />

                                    <input-field
                                        label="Term End"
                                        v-model="form.term_end"
                                        id="term_end"
                                        name="term_end"
                                        :form="form"
                                        field="term_end"
                                        type="date"
                                    />

                                    <input-field
                                        label="Next Term Begins"
                                        v-model="form.next_term"
                                        name="next_term"
                                        id="next_term"
                                        :form="form"
                                        field="next_term"
                                        type="date"
                                    />

                                    <input-field
                                        label="Result Header"
                                        v-model="form.header"
                                        name="header"
                                        id="header"
                                        :form="form"
                                        field="header"
                                        type="text"
                                    />

                                    <select-input
                                        name="gradinggroup_id"
                                        id="gradinggroup_id"
                                        v-model="form.gradinggroup_id"
                                        :options="gradinggroup"
                                        label="Grading Group"
                                        optionLabel="group_name"
                                        optionValue="id"
                                        :form="form"
                                        field="group_id"
                                        placeholder="select gradding group"
                                    />
                                </div>
                            </div>
                            <div class="row col-md-12">
                                <div class="form-group col-md-3">
                                    <label>CA1 Percentage:</label>
                                    <input
                                        v-model="form.ca1Percent"
                                        type="text"
                                        name="term_start"
                                        placeholder="Eg. 30%"
                                        class="form-control"
                                    />
                                </div>
                                <div class="form-group col-md-3">
                                    <label>CA2 Percentage:</label>
                                    <input
                                        v-model="form.ca2Percent"
                                        type="text"
                                        name="term_start"
                                        placeholder="Eg. 70%"
                                        class="form-control"
                                    />
                                </div>

                                <div class="form-group col-md-3">
                                    <label>CA3 Percentage:</label>
                                    <input
                                        v-model="form.ca3Percent"
                                        type="text"
                                        name="term_start"
                                        placeholder="Eg. 70%"
                                        class="form-control"
                                    />
                                </div>
                                <div class="form-group col-md-3">
                                    <label>Exams Percentage:</label>
                                    <input
                                        v-model="form.examPercent"
                                        type="text"
                                        name="term_start"
                                        placeholder="Eg. 70%"
                                        class="form-control"
                                    />
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-3">
                                    <label>Class position</label>
                                    <toggle-button
                                        :label="true"
                                        id="isAttendance"
                                        :labels="{
                                            checked: 'Yes',
                                            unchecked: 'No',
                                        }"
                                        v-model="form.isClassPosition"
                                        :color="'green'"
                                        :sync="true"
                                    />
                                </div>

                                <div class="form-group col-md-3">
                                    <label>Arm Position</label>
                                    <toggle-button
                                        :label="true"
                                        id="isAttendance"
                                        :labels="{
                                            checked: 'Yes',
                                            unchecked: 'No',
                                        }"
                                        v-model="form.isArmPosition"
                                        :color="'navy'"
                                        :sync="true"
                                    />
                                </div>

                                <div class="form-group col-md-3">
                                    <label> CA2</label>
                                    <toggle-button
                                        :label="true"
                                        id="ca2"
                                        :labels="{
                                            checked: 'ON',
                                            unchecked: 'OFF',
                                        }"
                                        v-model="form.isCa2"
                                        :color="'navy'"
                                        :sync="true"
                                    />
                                </div>
                                <div class="form-group col-md-3">
                                    <label> CA3</label>
                                    <toggle-button
                                        :label="true"
                                        id="ca3"
                                        :labels="{
                                            checked: 'ON',
                                            unchecked: 'OFF',
                                        }"
                                        v-model="form.isCa3"
                                        :color="'navy'"
                                        :sync="true"
                                    />
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-3">
                                    <label> Attendance</label>
                                    <toggle-button
                                        :label="true"
                                        id="ca3"
                                        :labels="{
                                            checked: 'ON',
                                            unchecked: 'OFF',
                                        }"
                                        v-model="form.isAttendance"
                                        :color="'navy'"
                                        :sync="true"
                                    />
                                </div>
                                <div class="form-group col-md-3">
                                    <label> Learning Domain</label>
                                    <toggle-button
                                        :label="true"
                                        id="learning"
                                        :labels="{
                                            checked: 'ON',
                                            unchecked: 'OFF',
                                        }"
                                        v-model="form.isLearningDomain"
                                        :color="'navy'"
                                        :sync="true"
                                    />
                                </div>
                                <div class="form-group col-md-3">
                                    <label> P.Comment</label>
                                    <toggle-button
                                        :label="true"
                                        id="ca3"
                                        :labels="{
                                            checked: 'ON',
                                            unchecked: 'OFF',
                                        }"
                                        v-model="form.isPrincipalComment"
                                        :color="'navy'"
                                        :sync="true"
                                    />
                                </div>
                                <div class="form-group col-md-3">
                                    <label> T.Comment</label>
                                    <toggle-button
                                        :label="true"
                                        id="ca3"
                                        :labels="{
                                            checked: 'ON',
                                            unchecked: 'OFF',
                                        }"
                                        v-model="form.isTeacherComment"
                                        :color="'navy'"
                                        :sync="true"
                                    />
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-3">
                                    <label> Progrss Status</label>
                                    <toggle-button
                                        :label="true"
                                        id="progess"
                                        :labels="{
                                            checked: 'ON',
                                            unchecked: 'OFF',
                                        }"
                                        v-model="form.isProgressStatus"
                                        :color="'navy'"
                                        :sync="true"
                                    />
                                </div>
                                <div class="form-group col-md-3">
                                    <label> Max Score</label>
                                    <toggle-button
                                        :label="true"
                                        id="ca3"
                                        :labels="{
                                            checked: 'ON',
                                            unchecked: 'OFF',
                                        }"
                                        v-model="form.isMaxScore"
                                        :color="'navy'"
                                        :sync="true"
                                    />
                                </div>
                                <div class="form-group col-md-3">
                                    <label> Min Score</label>
                                    <toggle-button
                                        :label="true"
                                        id="ca3"
                                        :labels="{
                                            checked: 'ON',
                                            unchecked: 'OFF',
                                        }"
                                        v-model="form.isMinScore"
                                        :color="'navy'"
                                        :sync="true"
                                    />
                                </div>
                                <div class="form-group col-md-3">
                                    <label> Subj. Position</label>
                                    <toggle-button
                                        :label="true"
                                        id="ca3"
                                        :labels="{
                                            checked: 'ON',
                                            unchecked: 'OFF',
                                        }"
                                        v-model="form.isSubjectPosition"
                                        :color="'navy'"
                                        :sync="true"
                                    />
                                </div>
                                <div class="form-group col-md-3">
                                    <label> M.P.Comment</label>
                                    <toggle-button
                                        :label="true"
                                        id="pcm"
                                        :labels="{
                                            checked: 'ON',
                                            unchecked: 'OFF',
                                        }"
                                        v-model="form.isManualPrincipalComment"
                                        :color="'navy'"
                                        :sync="true"
                                    />
                                </div>
                                <div class="form-group col-md-3">
                                    <label> DOB</label>
                                    <toggle-button
                                        :label="true"
                                        id="isDob"
                                        :labels="{
                                            checked: 'ON',
                                            unchecked: 'OFF',
                                        }"
                                        v-model="form.isDob"
                                        :color="'navy'"
                                        :sync="true"
                                    />
                                </div>
                                <div class="form-group col-md-3">
                                    <label> Gender</label>
                                    <toggle-button
                                        :label="true"
                                        id="isGender"
                                        :labels="{
                                            checked: 'ON',
                                            unchecked: 'OFF',
                                        }"
                                        v-model="form.isGender"
                                        :color="'navy'"
                                        :sync="true"
                                    />
                                </div>
                                <div class="form-group col-md-3">
                                    <label> Use Cummulative</label>
                                    <toggle-button
                                        :label="true"
                                        id="isCummulative"
                                        :labels="{
                                            checked: 'ON',
                                            unchecked: 'OFF',
                                        }"
                                        v-model="form.isCummulative"
                                        :color="'navy'"
                                        :sync="true"
                                    />
                                </div>
                                <div class="form-group col-md-3">
                                    <label> Use Weekly CA</label>
                                    <toggle-button
                                        :label="true"
                                        id="isWeeklyCA"
                                        :labels="{
                                            checked: 'ON',
                                            unchecked: 'OFF',
                                        }"
                                        v-model="form.isWeeklyCa"
                                        :color="'navy'"
                                        :sync="true"
                                    />
                                </div>
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
                                v-show="!editmode"
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

        <div
            class="modal fade"
            id="summaryModal"
            tabindex="-1"
            role="dialog"
            aria-labelledby="exampleModalLabel"
            aria-hidden="true"
        >
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="example">
                            {{
                                isMaster
                                    ? "View Master Sheet"
                                    : "computeSummary"
                            }}
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
                            isMaster ? viewMaster() : computeSummary()
                        "
                    >
                        <div class="modal-body">
                            <div class="form-group">
                                <select
                                    name="level_id"
                                    id="level_id"
                                    class="form-control"
                                    v-model="computeForm.arm_id"
                                >
                                    <option value selected>Select Level</option>
                                    <option
                                        v-for="arm in arms"
                                        :key="arm.arms.id"
                                        :value="arm.arms.id"
                                    >
                                        {{ arm.arms.name }}
                                    </option>
                                    <option value="" selected>All Arms</option>
                                </select>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button
                                type="button"
                                class="btn btn-secondary"
                                data-dismiss="modal"
                            >
                                Close
                            </button>
                            <button class="btn btn-primary">
                                {{ isMaster ? "view" : "compute" }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div
            class="modal fade"
            id="activationModal"
            tabindex="-1"
            role="dialog"
            aria-labelledby="exampleModalLabel"
            aria-hidden="true"
        >
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="example">
                            Activate Result
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
                    <form @submit.prevent="getActivated()">
                        <div class="modal-body">
                            <div class="form-group">
                                <input
                                    class="form-control"
                                    type="text"
                                    placeholder="Enter Activation Key"
                                    :class="{
                                        'is-invalid':
                                            activationForm.errors.has(
                                                'activation_key'
                                            ),
                                    }"
                                    v-model="activationForm.activation_key"
                                />
                                <has-error
                                    :form="activationForm"
                                    field="activation_key"
                                ></has-error>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button
                                type="button"
                                class="btn btn-secondary"
                                data-dismiss="modal"
                            >
                                Close
                            </button>
                            <button class="btn btn-primary">
                                Save changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import SelectedItems from "vue-gridmultiselect/src/components/SelectedItems.vue";
import Loading from "vue-loading-overlay";
import SelectInput from "./utils/SelectInput.vue";
export default {
    components: { Loading, SelectedItems, SelectInput },
    data() {
        return {
            isLoading: false,
            fullPage: true,
            editmode: false,
            levels: {},
            sessions: {},
            terms: {},
            gradinggroup: [],
            arms: {},
            reportFormat: [
                { id: 1, label: "Use Cummulative" },
                { id: 0, label: "Use Terminal" },
            ],
            isNotActivated: true,
            isActivationKey: false,
            isStudent: window.user.student_id,
            reports: [],
            arm_id: "",
            report_id: "",
            isMaster: false,
            schoolTemplates: [],
            form: new Form({
                id: "",
                title: "",
                level_id: "",
                term_id: "",
                session_id: "",
                type: "",
                school_days: "",
                arm_id: "",
                next_term: "",
                term_start: "",
                term_end: "",
                header: "",
                gradinggroup_id: "",
                isCummulative: 0,
                school_id: window.user.school_id,
                isCa2: false,
                isCa3: false,
                isWeeklyCa: false,
                isAttendance: false,
                isPrincipalComment: false,
                isTeacherComment: false,
                isProgressStatus: false,
                isMaxScore: true,
                isMinScore: true,
                isSubjectPosition: true,
                isArmPosition: false,
                isClassPosition: false,
                ca1Percent: "",
                ca2Percent: "",
                ca3Percent: "",
                examPercent: "",
                isManualPrincipalComment: "",
                isLearningDomain: true,
                isDob: true,
                isGender: true,
            }),

            computeForm: new Form({
                arm_id: "",
                report_id: "",
            }),
            activationForm: new Form({
                activation_key: "",
                report_id: "",
            }),
        };
    },
    mounted() {
        axios.get("api/get_levels").then((res) => {
            this.levels = res.data;
        });

        axios.get("api/term").then((res) => {
            this.terms = res.data;
        });

        axios.get("api/load_session").then((res) => {
            this.sessions = res.data;
        });
    },

    methods: {
        getResults(page = 1) {
            axios.get("api/report?page=" + page).then((response) => {
                this.reports = response.data;
            });
        },
        updateReport(id) {
            this.$Progress.start();

            this.form
                .put("/api/report")
                .then(() => {
                    // success
                    $("#addNew").modal("hide");
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
        editModal(level) {
            this.editmode = true;
            this.form.reset();
            $("#addNew").modal("show");
            this.form.fill(level);
            console.log([this.form.isCa2, this.form.isCa3]);
        },
        newModal() {
            this.editmode = false;
            this.form.reset();
            $("#addNew").modal("show");
        },

        activateReport(id) {
            axios
                .post("/api/activate_report/" + id)
                .then(() => {
                    toast.fire({
                        type: "success",
                        title: "Report activated successfully",
                    });
                    Fire.$emit("AfterCreate");
                })
                .catch(() => {});
        },
        download(id) {
            location.replace(`/download_page/${id}`);
        },
        CreateReport() {
            this.$Progress.start();

            this.form
                .post("api/report")
                .then(() => {
                    Fire.$emit("AfterCreate");
                    $("#addNew").modal("hide");

                    toast.fire({
                        type: "success",
                        title: "Student Created in successfully",
                    });
                    this.$Progress.finish();
                    $("#addNew").modal("hide");
                    Fire.$emit("AfterCreate");
                })
                .catch(() => {});
        },
        deleteReport(id) {
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
                        .delete("api/report/" + id)
                        .then(() => {
                            swal.fire(
                                "Deleted!",
                                "level has been deleted.",
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
        loadReports() {
            if (this.$gate.isAdminOrTutorOrStudentOrParent()) {
                axios.get("api/report").then((response) => {
                    this.reports = response.data;
                });
            }
        },
        showActivationModal(id) {
            $("#activationModal").modal("show");
            this.activationForm.reset();
            this.activationForm.report_id = id;
        },
        getActivated() {
            this.activationForm
                .post("api/get_activated")
                .then((res) => {
                    if (res.data.already_used) {
                        swal.fire(
                            "fail!",
                            "This key has alreay been used.",
                            "error"
                        );
                    } else if (res.data.invalid_key) {
                        swal.fire("Invalid!", "Invalid Key.", "warning");
                    } else if (res.data.success) {
                        swal.fire(
                            "success",
                            "Result activated Successfully.",
                            "success"
                        );
                        $("#activationModal").modal("hide");
                        Fire.$emit("AfterCreate");
                    }
                })
                .catch((err) => {
                    swal.fire("fail!", err, "failure");
                });
        },

        loadArms(id) {
            axios.get("/api/getArms/" + id).then((res) => {
                this.arms = res.data;
                this.computeForm.report_id = id;

                $("#summaryModal").modal("show");
                console.log(res.data);
            });
        },

        getGradinggroup() {
            axios
                .get("api/gradinggroup")
                .then((res) => (this.gradinggroup = res.data));
        },
        computeSummary() {
            this.setLoading();
            axios
                .post(
                    "api/computeSummary/" +
                        this.computeForm.report_id +
                        "/" +
                        this.computeForm.arm_id
                )
                .then((res) => {
                    if (res.data.message === "success") {
                        $("#summaryModal").modal("hide");
                        swal.fire(
                            "success",
                            "Summary computed successfully",
                            "success"
                        );

                        this.resetLoading();
                    } else if (res.data.message === "no record") {
                        swal.fire("error", "No results found", "error");
                        $("#activationModal").modal("hide");
                        Fire.$emit("AfterCreate");
                        this.resetLoading();
                    }
                })
                .catch((err) => {
                    swal.fire("fail!", "server errors", "failure");

                    this.resetLoading();
                });

            //  this.resetLoading()
        },

        setLoading() {
            this.isLoading = true;
        },
        resetLoading() {
            this.isLoading = false;
        },
        loadMasterArms(id) {
            // const  report=   this.reports.data.find(element=>element.id===id);
            this.loadArms(id);
            this.report_id = id;
            this.isMaster = true;
        },
        loadSchoolTemplate() {
            let defaultTemplate = [
                {
                    id: 1,
                    name: "default-result",
                },
            ];
            axios
                .get(`/api/schoolTemplates`)
                .then((res) => {
                    if (res.data.length) {
                        this.schoolTemplates = res.data;
                    } else {
                        this.schoolTemplates = defaultTemplate;
                    }
                })
                .catch((err) => console.log(err));
        },

        viewMaster() {
            $("#summaryModal").modal("hide");
            this.$router.push(
                `/master/${this.report_id}/${
                    this.computeForm.arm_id ? this.computeForm.arm_id : ""
                }`
            );
            this.isMaster = false;
        },
    },
    created() {
        this.getGradinggroup();
        this.loadSchoolTemplate();
        // console.log(window.user)
        Fire.$on("searching", () => {
            let query = this.$parent.search;
            axios
                .get("api/findStudent?q=" + query)
                .then((data) => {
                    this.levels = data.data;
                })
                .catch(() => {});
        });

        this.loadReports();
        Fire.$on("AfterCreate", () => {
            this.loadReports();
        });
        //    setInterval(() => this.loadUsers(), 3000);
    },
};
</script>
