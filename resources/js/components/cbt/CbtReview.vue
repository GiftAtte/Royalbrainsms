<template>
    <div>
        <div class="content-header">
            <div class="container-fluid navy">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Answer Sheet</h1>
                    </div>
                    <!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">
                                <a href="#">Home</a>
                            </li>
                            <li class="breadcrumb-item active">Exam Config</li>
                        </ol>
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </div>
        <div class="col-md-12">
            <div class="card card-navy card-outline">
                <div class="card-header">
                    <button
                        v-show="$gate.isAdminOrStudent()"
                        class="btn btn-primary float-right pl-2"
                        @click="printResults"
                    >
                        <i class="fa fa-print"></i>Print Sheet
                    </button>
                </div>

                <div class="card-body" id="section-to-print">
                    <h3 class="mt-5 text-center">{{ exam.title }}</h3>

                    <h4 class="ml-5">
                        {{ student.surname }}
                        {{ student.first_name }} ------------
                        {{ student.levels.level_name }}
                    </h4>
                    <h4 class="ml-5 text-danger">
                        Score: {{ score + "/" + questions.length }} =====> ({{
                            Number.parseFloat(
                                (score / questions.length) * 100
                            ).toPrecision(4) + "%"
                        }})
                    </h4>
                    <hr />
                    <div
                        v-for="(question, index) in questions"
                        :key="question.id"
                        class="col pb-1 ml-3"
                    >
                        <div class="row">
                            <span>{{ index + 1 }}.</span>
                            <p v-html="question.question.question"></p>

                            <i
                                v-if="question.question.is_right"
                                class="fa fa-check green ml-5"
                                aria-hidden="true"
                                title="right answer"
                                v-show="question.question.is_right"
                            ></i>
                            <i
                                class="fa fa-times red ml-5"
                                aria-hidden="true"
                                title="wrong answer"
                                v-if="!question.question.is_right"
                            ></i>
                        </div>

                        <div
                            v-for="options in question.options"
                            :key="options.id"
                            class="row"
                        >
                            <div class="icheck-success icheck-inline">
                                <input
                                    disabled
                                    type="radio"
                                    :id="`q${options.id}`"
                                    :value="
                                        options.option_id
                                            ? options.option_id
                                            : ''
                                    "
                                    :name="`q${question.question.id}`"
                                    :checked="
                                        question.student_answer &&
                                        question.student_answer.option_id ===
                                            options.option_id
                                    "
                                />

                                <label :for="`q${options.id}`"
                                    >{{ options.options.option }}: &nbsp;
                                    {{ options.option_value }}</label
                                >
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="!$gate.isAdminOrTutorOrStudent()">
            <not-found></not-found>
        </div>
        <!-- Arms Modal -->

        <!-- Level Modal -->
    </div>
</template>

<script>
import moment from "moment";
export default {
    data() {
        return {
            editmode: false,
            isExam: false,
            isSuccess: 0,
            score: 0,
            exams: {},
            exam: "",
            student: "",
            subjects: {},
            questions: [],
            student_answer: {},
            arms: {},
            start_date: "",
            end_date: "",
            start_time: "",
            form: new Form({
                id: "",
                exam_id: this.$route.params.id,
                questions: [],
            }),
        };
    },
    mounted() {
        axios.get("/api/get_levels").then((res) => {
            this.levels = res.data;
        });
    },
    methods: {
        tickAnswers(studentOption, option) {
            if (studentOption === right_option) {
                return true;
            }
            return false;
        },

        getResults(page = 1) {
            axios.get("api/exams?page=" + page).then((response) => {
                this.exams = response.data;
            });
        },

        onComplete() {
            console.log("finished");
        },
        deleteExam(id) {
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
                        .delete("api/exams/" + id)
                        .then(() => {
                            swal.fire(
                                "Deleted!",
                                "exam has been deleted.",
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

        loadQuestions() {
            if (this.$gate.isAdminOrTutorOrStudent()) {
                axios
                    .get(
                        `/api/review_answers/${this.$route.params.exam_id}/${
                            this.$route.params.student_id
                                ? this.$route.params.student_id
                                : ""
                        }`
                    )
                    .then((res) => {
                        this.questions = res.data.cbt;
                        this.exam = res.data.exam;
                        this.score = res.data.score;
                        this.student = res.data.student;
                        this.score = res.data.score;
                        this.student_answer = res.data.student_answer;

                        console.log(this.score);
                    });
            }
        },

        checkSubmit() {
            if (this.isSuccess) {
                return true;
            } else {
                return false;
            }
        },
        startExam() {
            let now = moment(new Date()).format("YYYY-MM-DD h:mm:ss a");
            let date_diff = moment(now).diff(
                moment(this.exam.end_date).format("YYYY-MM-DD h:mm:ss a")
            );
            if (date_diff > 0) {
                swal.fire({
                    title: "Test ended",
                    text: "Sorry! This test has ended",
                    type: "warning",
                });
                return false;
            }
            if (
                moment(now).diff(
                    moment(this.exam.start_date).format("YYYY-MM-DD h:mm:ss a")
                ) < 0
            ) {
                swal.fire({
                    title: "Not Started",
                    text: "Sorry! This test has not started yet",
                    type: "warning",
                });
                return false;
            } else {
                return true;
            }
        },
        saveAnswers() {
            swal.fire({
                title: "Are you sure?",
                text: "You won't be able access the questions once submitted !",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, submit !",
            }).then((result) => {
                // Send request to the server
                if (result.value) {
                    this.form.questions = [];
                    let selected;
                    var isSuccess = false;
                    for (
                        let index = 0;
                        index < this.questions.length;
                        index++
                    ) {
                        selected = false;
                        let option = document.getElementsByName(
                            `q${this.questions[index].question.id}`
                        );

                        for (let i = 0; i < option.length; i++) {
                            if (option[i].checked) {
                                selected = option[i];

                                break;
                            }
                        }
                        if (selected) {
                            this.form.questions.push({
                                option_id: selected.value,
                                question_id: this.questions[index].question.id,
                            });
                        }
                    }

                    this.form
                        .post("/api/save_answers")

                        .then((res) => {
                            this.isSuccess = 1;
                            this.score = res.data;
                            this.score =
                                (this.score / this.questions.length) * 100;
                        })
                        .catch((err) => {
                            this.isSuccess = 0;
                        });
                }
            });
        },
        printResults() {
            window.print();
        },
    },
    created() {
        this.loadQuestions();
        Fire.$on("AfterCreate", () => {
            this.loadExams();
        });
        //    setInterval(() => this.loadUsers(), 3000);
    },
};
</script>
