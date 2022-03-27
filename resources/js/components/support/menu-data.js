export default {
    // home is a section without childs, set as an empty array
    home: [
        {
            type: "title",
            txt: "Home",
            icon: "fa fa-tag context-menu__title-icon"
        },
        {
            type: "link",
            txt: "Dashboard",
            link: "/dashboard"
        }
    ],

    manage: [
        {
            type: "title",
            txt: "Manage",
            icon: "fas fas fa-tachometer-alt context-menu__title-icon"
        },

        {
            type: "link",
            txt: "Students",
            link: "/students"
        },

        {
            type: "link",
            txt: "Users",
            link: "/users"
        },

        {
            type: "link",
            txt: "Employees",
            link: "/staff"
        },
        {
            type: "link",
            txt: "Levels",
            link: "/levels"
        },
        {
            type: "link",
            txt: "Level Arms",
            link: "/arm_list"
        },
        {
            type: "link",
            txt: "Subjects",
            link: "/subjects"
        },
        {
            type: "link",
            txt: "Level Subjects",
            link: "/level_subjects"
        },
        {
            type: "link",
            txt: "Subject Teacher",
            link: "/subject_teacher"
        },

        {
            type: "link",
            txt: "Level History",
            link: "/level_history"
        },
        {
            type: "link",
            txt: "Download Login Details",
            link: "/exports"
        },

        {
            type: "link",
            txt: "Upload  Zipe Images",
            link: "/photos"
        }
    ],

    reports: [
        {
            type: "title",
            txt: "Reports",
            icon: "fa fa-users context-menu__title-icon"
        },

        {
            type: "link",
            txt: "Report Card",
            link: "/reports"
        },

        {
            type: "link",
            txt: "Score Card",
            link: "/scores"
        },

        {
            type: "link",
            txt: "Creche" + "'s Comments",
            link: "/creche_comment"
        },
        {
            type: "link",
            txt: "Backup Scores",
            link: "/backup"
        },
        {
            type: "link",
            txt: "Activate Results",
            link: "/load_activation"
        },
        {
            type: "link",
            txt: "Manage Promotion",
            link: "/manage_progress"
        },
        {
            type: "link",
            txt: "Performance Tracker",
            link: "/chart"
        },
        {
            type: "link",
            txt: "Subject Performance",
            link: "/subject_tracker"
        },
        {
            type: "link",
            txt: "Assignment-Notes",
            link: "/assignments"
        },

        {
            type: "link",
            txt: "Learning Domain",
            link: "/learning_domain_grading"
        },
        {
            type: "link",
            txt: "Videos",
            link: "/video_list"
        }
    ],
    // student accademics
    accademics: [
        {
            type: "title",
            txt: "Academics",
            icon: "fa fa-graduation-cap blue context-menu__title-icon"
        },
        {
            type: "link",
            txt: "Report Card",
            link: "/reports"
        },
        {
            type: "link",
            txt: "Videos",
            link: "/video_list"
        },
        {
            type: "link",
            txt: "Coming Up",
            link: "#"
        },
        {
            type: "link",
            txt: "Assignment-Notes",
            link: "/assignments"
        },
        {
            type: "link",
            txt: "My Subjects",
            link: "/subjects"
        },
        {
            type: "link",
            txt: "Performance Tracker",
            link: "/chart"
        },
        {
            type: "link",
            txt: "Subject Performance",
            link: "/subject_tracker"
        },
        {
            type: "link",
            txt: "Materials",
            link: "#"
        },
        {
            type: "link",
            txt: "Articles-Upload",
            link: "/assignments"
        },
        {
            type: "link",
            txt: "CBT",
            link: "/exam_list"
        }
    ],

    // admin settings
    settings: [
        {
            type: "title",
            txt: "Settings",
            icon: "fa fa-key blue context-menu__title-icon"
        },

        {
            type: "link",
            txt: "Academic Sessions",
            link: "/academic_sessions"
        },
        {
            type: "link",
            txt: "Activation Keys",
            link: "/activation_keys"
        },

        {
            type: "link",
            txt: "Principal" + "'s Comments_Bank",
            link: "/principalComments"
        },

        {
            type: "link",
            txt: "Manual Principal" + "'s Comments",
            link: "/principalComment"
        },

        {
            type: "link",
            txt: " Auto Principal" + "'s Comments",
            link: "/comment"
        },

        {
            type: "link",
            txt: "Teacher" + "'s Comments",
            link: "/tutor_comments"
        },
        {
            type: "link",
            txt: "Comment Bank",
            link: "/teachers_comment"
        },
        {
            type: "link",
            txt: "Creche Domain",
            link: "/creche_domain"
        },

        {
            type: "link",
            txt: "Creche Ratings",
            link: "/creche_ratings"
        },

        {
            type: "link",
            txt: "Grading Group",
            link: "/grading_group"
        },
        {
            type: "link",
            txt: "Grading System",
            link: "/gradings"
        },
        {
            type: "link",
            txt: "Result Config",
            link: "/configs"
        },
        {
            type: "link",
            txt: "Learning Domain",
            link: "/learning_domain"
        },

        {
            type: "link",
            txt: "SMS Message",
            link: "/sms"
        }
    ],
    //superadmin
    superadmin: [
        {
            type: "title",
            txt: "Settings",
            icon: "fa fa-key blue context-menu__title-icon"
        },
        {
            type: "link",
            txt: "Schools",
            link: "/school"
        },

        {
            type: "link",
            txt: "Activation Keys",
            link: "/activation_keys"
        },

        {
            type: "link",
            txt: "Manage Payment API",
            link: "/payment_api"
        },
        {
            type: "link",
            txt: "Manage SMS",
            link: "/manage_sms"
        }
    ],
    account: [
        {
            type: "title",
            txt: "My Account",
            icon: "fa fa-user context-menu__title-icon"
        },

        {
            type: "link",
            txt: "Profile",
            link: "/staff_profile"
        },

        {
            type: "link",
            txt: "Logout",
            link: "/logout"
        }
    ],

    // student Account
    student_account: [
        {
            type: "title",
            txt: "My Account",
            icon: "fa fa-user context-menu__title-icon"
        },

        {
            type: "link",
            txt: "Profile",
            link: "/student_profile"
        },

        {
            type: "link",
            txt: "Logout",
            link: "/logout"
        }
    ],

    // CBT
    cbt: [
        {
            type: "title",
            txt: "CBT",
            icon: "fa fa-pencil context-menu__title-icon"
        },

        {
            type: "link",
            txt: "Schedul Exam",
            link: "/exams"
        },

        {
            type: "link",
            txt: "Exam List",
            link: "/exam_list"
        }
    ],

    // Chat Routs
    chat: [
        {
            type: "title",
            txt: "Chats",
            icon: "fa fa-coversation context-menu__title-icon"
        },

        {
            type: "link",
            txt: "Class chat",
            link: "/level_chats"
        },

        {
            type: "link",
            txt: "School Chat",
            link: "/chats"
        }
    ],

    tutor: [
        {
            type: "title",
            txt: "Form Tutor",
            icon: "fa fa-pencil context-menu__title-icon"
        },
        {
            type: "link",
            txt: "Subject Teacher",
            link: "/subject_teacher"
        },
        {
            type: "link",
            txt: "Students",
            link: "/students"
        },
        {
            type: "link",
            txt: "Report Card",
            link: "/reports"
        },
        {
            type: "link",
            txt: "Manage Promotion",
            link: "/manage_progress"
        },
        {
            type: "link",
            txt: "Score Card",
            link: "/scores"
        },
        {
            type: "link",
            txt: "Backup Scores",
            link: "/backup"
        },
        {
            type: "link",
            txt: "Comment Bank",
            link: "/teachers_comment"
        },
        {
            type: "link",
            txt: "Teacher" + "'s Comments",
            link: "/tutor_comments"
        },
        {
            type: "link",
            txt: "Creche" + "'s Comments",
            link: "/creche_comment"
        },

        {
            type: "link",
            txt: "Learning Domain",
            link: "/learning_domain_grading"
        },
        {
            type: "link",
            txt: "Videos",
            link: "/video_list"
        },

        {
            type: "link",
            txt: "Performance Tracker",
            link: "/chart"
        },
        {
            type: "link",
            txt: "Subject Performance",
            link: "/subject_tracker"
        },
        {
            type: "link",
            txt: "Assignment-Notes",
            link: "/assignments"
        }
    ],
    fees: [
        {
            type: "title",
            txt: "Fee Management",
            icon: "fa fa-pencil context-menu__title-icon"
        },

        {
            type: "link",
            txt: "Fee Group",
            link: "/feegroup"
        },
        {
            type: "link",
            txt: "E-Wallet",
            link: "/ewallets"
        },

        {
            type: "link",
            txt: "Bills",
            link: "/bills"
        }
    ]
};
