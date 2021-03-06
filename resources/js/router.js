import Vue from 'vue'
import Router from 'vue-router'

Vue.use(Router)

export default new Router({
  mode: 'history',

  routes: [

    // loads Home component
    { path: '/', component: require('./components/Dashboard.vue').default },
    { path: ' /home', component: require('./components/Dashboard.vue').default },
    { path: '/dashboard', component: require('./components/Dashboard.vue').default },
    { path: '/developer', component: require('./components/Developer.vue').default },
    { path: '/users', component: require('./components/Users.vue').default },
    { path: '/staff_profile/:id?', component: require('./components/staff/Staff_profile.vue').default },
    { path: '/staff', component: require('./components/Employee.vue').default },
    { path: '/chart', component: require('./components/students/Chart.vue').default },
    { path: '/students', component: require('./components/students/Student_list.vue').default },
    { path: '/levels', component: require('./components/students/Level.vue').default },
    { path: '/levels/:id', component: require('./components/students/Arms.vue').default },
    { path: '/subjects', component: require('./components/subject/Subject.vue').default },
    { path: '/level_subjects', component: require('./components/subject/Level_subjects.vue').default },
    { path: '/student_profile/:id?', component: require('./components/students/Student_profile.vue').default },
    { path: '/result_list/:id', component: require('./components/results/Result_list.vue').default },
    { path: '/result/:report_id/:student_id?/:annual?', component: require('./components/results/Result.vue').default },
    { path: '/manage_progress', component: require('./components/results/Promotion.vue').default },
    { path: '/configs', component: require('./components/results/Config.vue').default },
    { path: '*', component: require('./components/NotFound.vue').default },
    { path: '/chart', component: require('./components/students/Chart.vue').default },
    { path: '/reports/:message?', component: require('./components/Report.vue').default },
    { path: '/scores', component: require('./components/subject/Marks.vue').default },
    { path: '/import_scores', component: require('./components/students/Import_scores.vue').default },
    { path: '/sms', component: require('./components/admin/Mail.vue').default },
    { path: '/load_activation', component: require('./components/admin/Activate_results.vue').default },
    { path: '/import_students', component: require('./components/students/Import_student.vue').default },
    { path: '/assignments', component: require('./components/assignments/Assignment_note.vue').default },
    { path: '/assignment_view/:id', component: require('./components/assignments/Assignment_view.vue').default },
    { path: '/arm_list', component: require('./components/students/New_arms.vue').default },
    { path: '/photos', component: require('./components/students/Photos.vue').default },
    { path: '/school', component: require('./components/school/school.vue').default },
    { path: '/exports', component: require('./components/support/LoginDetails_export.vue').default },
    { path: '/logout', component: require('./components/support/Logout.vue').default },
    { path: '/academic_sessions', component: require('./components/Sessions.vue').default },
    { path: '/gradings', component: require('./components/Grade.vue').default },
    { path: '/comment', component: require('./components/admin/Comment.vue').default },
    { path: '/watch_video/:id', component: require('./components/videos/Video.vue',).default },
    { path: '/video_list', component: require('./components/videos/Upload.vue').default },
    { path: '/teachers_comment', component: require('./components/staff/Staffcomment.vue').default },
    { path: '/activation_keys', component: require('./components/admin/Activation_key.vue').default },
    { path: '/learning_domain', component: require('./components/admin/LearningDomain.vue').default },
    { path: '/manage_sms', component: require('./components/admin/Message.vue').default },
    { path: '/learning_domain_grading', component: require('./components/admin/StudentLD.vue').default },
    { path: '/deactivat_results', component: require('./components/admin/Deactivate.vue').default },
    { path: '/exam', component: require('./components/cbt/Exam.vue').default },
    { path: '/exams', component: require('./components/cbt/Exam_list.vue').default },
    { path: '/questions/:id', component: require('./components/cbt/Questions.vue').default },
    { path: '/edit/:exam_id/:question_id', component: require('./components/cbt/Questions.vue').default },
    { path: '/options/:id', component: require('./components/cbt/Options.vue').default },
    { path: '/exam/:id', component: require('./components/cbt/Question_list.vue').default },
    { path: '/exam_list', component: require('./components/cbt/Student_exam.vue').default },
    { path: '/cbt_scores/:id', component: require('./components/cbt/CbtScores.vue').default },
    { path: '/level_history', component: require('./components/students/LevelHistory.vue').default },
    { path: '/students/:id', component: require('./components/students/HistoryList.vue').default },
    { path: '/assign_arms/:id', component: require('./components/students/AssignArms.vue').default },
    { path: '/pdfviewer/:id', component: require('./components/assignments/PDFviewer.vue').default },
    { path: '/noteviewer/:id', component: require('./components/assignments/NoteToPDF.vue').default },
    { path: '/getstudents/:id', component: require('./components/assignments/StudentList.vue').default },
    { path: '/chats', component: require('./components/messaging/Chats.vue').default },
    { path: '/level_chats', component: require('./components/messaging/LevelChats.vue').default },
    { path: '/import_history', component: require('./components/students/ImportHistory.vue').default },
    { path: '/history_results', component: require('./components/students/HistoryScores.vue').default },
    { path: '/cbt_review/:exam_id/:student_id?', component: require('./components/cbt/CbtReview.vue').default },
    { path: '/master/:report_id', component: require('./components/results/Master.vue').default },
    { path: '/grading_group', component: require('./components/grading/Gradinggroup.vue').default },
    { path: '/transcript/:student_id?', component: require('./components/results/Transcript.vue').default },
    { path: '/feegroup', component: require('./components/fees/Feegroup.vue').default },
    { path: '/fee_description/:feegroup_id/:student_id?', component: require('./components/fees/FeeDescription.vue').default },
    { path: '/fee_list/:feegroup_id', component: require('./components/fees/Feelist.vue').default },
    { path: '/paystack_key', component: require('./components/fees/Paystack.vue').default },
    { path: '/subject_teacher', component: require('./components/subject/SubjectTeacher.vue').default },
    { path: '/tutor_comments', component: require('./components/staff/TutorsComment.vue').default },
    { path: '/creche_domain', component: require('./components/creche/Domain.vue').default },
    { path: '/subdomain/:id', component: require('./components/creche/Subdomains.vue').default },
    { path: '/creche_ratings', component: require('./components/creche/Rating.vue').default },
    { path: '/creche_comment', component: require('./components/creche/CrecheScores.vue').default },

    { path: '/backup', component: require('./components/results/Backup.vue').default },
    { path: '/not-found', component: require('./components/NotFound.vue').default }



  ]
})
