export default {
    // home is a section without childs, set as an empty array
    home: [
        {
            type: "title",
            txt: "Home",
            icon: "fa fa-tag context-menu__title-icon",
        },
        {
            type: "link",
            txt: "Dashboard",
            link: "/dashboard",
        },
    ],

    manage: [
        {
            type: "title",
            txt: "Manage",
            icon: "fas fas fa-tachometer-alt context-menu__title-icon",
        },
       {
            type: "link",
            txt: "Employees",
            link: "/staff",
        },
        {
            type: "link",
            txt: "Students",
            link: "/students",
        },

         {
            type: "link",
            txt: "Parents",
            link: "/parents",
        },
        // {
        //     type: "link",
        //     txt: "Users",
        //     link: "/users",
        // },


        {
            type: "link",
            txt: "Levels",
            link: "/levels",
        },

        {
            type: "link",
            txt: "Level Arms",
            link: "/arm_list",
        },
        {
            type: "link",
            txt: "Subjects",
            link: "/subjects",
        },
        {
            type: "link",
            txt: "Level Subjects",
            link: "/level_subjects",
        },
        {
            type: "link",
            txt: "Subject Teacher",
            link: "subject_teachers",
        },

        {
            type: "link",
            txt: "Assignment-Notes",
            link: "/assignments",
        },
        {
            type: "link",
            txt: "Videos",
            link: "/video_list",
        },
        {
            type: "link",
            txt: "Live Classes",
            link: "/liveClasses",
        },
        {
            type: "link",
            txt: "Manual Attendance",
            link: "/daily/attendance",
        },
        {
            type: "link",
            txt: "Biometric Attendance",
            link: "/attendance/biometric",
        },
    ],

    reports: [
        {
            type: "title",
            txt: "Reports",
            icon: "fa fa-users context-menu__title-icon",
        },

        {
            type: "link",
            txt: "Report Card",
            link: "/reports",
        },

        {
            type: "link",
            txt: "Score Card",
            link: "/scores",
        },

        {
            type: "link",
            txt: "Creche" + "'s Comments",
            link: "/creche_comment",
        },
        {
            type: "link",
            txt: "Attendance",
            link: "/attendance",
        },
        {
            type: "link",
            txt: "Backup Scores",
            link: "/backup",
        },
        {
            type: "link",
            txt: "Activate Results",
            link: "/load_activation",
        },
        {
            type: "link",
            txt: "Manage Promotion",
            link: "/manage_progress",
        },
        {
            type: "link",
            txt: "Performance Tracker",
            link: "/chart",
        },
        {
            type: "link",
            txt: "Subject Performance",
            link: "/subject_tracker",
        },

        {
            type: "link",
            txt: "Learning Domain",
            link: "/learning_domain_grading",
        },
        {
            type: "link",
            txt: "Weekly Activity",
            link: "/weekly/marks",
        },
        {
            type: "link",
            txt: "Certificates",
            link: "/certificates",
        },
        {
            type: "link",
            txt: "Certifications",
            link: "/certifications",
        },
    ],
    // student accademics
    accademics: [
        {
            type: "title",
            txt: "Academics",
            icon: "fa fa-graduation-cap blue context-menu__title-icon",
        },
        {
            type: "link",
            txt: "Report Card",
            link: "/reports",
        },
        {
            type: "link",
            txt: "Videos",
            link: "/video_list",
        },
        {
            type: "link",
            txt: "Coming Up",
            link: "#",
        },
        {
            type: "link",
            txt: "Assignment-Notes",
            link: "/assignments",
        },
        {
            type: "link",
            txt: "My Subjects",
            link: "/subjects",
        },
        {
            type: "link",
            txt: "Performance Tracker",
            link: "/chart",
        },
        {
            type: "link",
            txt: "Subject Performance",
            link: "/subject_tracker",
        },
        {
            type: "link",
            txt: "Materials",
            link: "#",
        },
        {
            type: "link",
            txt: "Articles-Upload",
            link: "/assignments",
        },
        {
            type: "link",
            txt: "CBT",
            link: "/exam_list",
        },
        {
            type: "link",
            txt: "Live Classes",
            link: "/liveClasses",
        },
       
    ],

    // PARENTS DOMAIN

    parent: [
        {
            type: "title",
            txt: "Parent Conner",
            icon: "fa fa-user white context-menu__title-icon",
        },
        {
            type: "link",
            txt: "Siblings",
            link: "/siblings",
        },
        {
            type: "link",
            txt: "Report Card",
            link: "/reports",
        },
        {
            type: "link",
            txt: "Videos",
            link: "/video_list",
        },
        {
            type: "link",
            txt: "Coming Up",
            link: "#",
        },
        {
            type: "link",
            txt: "Assignment-Notes",
            link: "/assignments",
        },

        {
            type: "link",
            txt: "Performance Tracker",
            link: "/chart",
        },
        {
            type: "link",
            txt: "Subject Performance",
            link: "/subject_tracker",
        },
        {
            type: "link",
            txt: "Materials",
            link: "#",
        },
        {
            type: "link",
            txt: "Articles-Upload",
            link: "/assignments",
        },
        {
            type: "link",
            txt: "CBT",
            link: "/exam_list",
        },
    ],

    // admin settings
    settings: [
        {
            type: "title",
            txt: "Settings",
            icon: "fa fa-key blue context-menu__title-icon",
        },

        {
            type: "link",
            txt: "Academic Sessions",
            link: "/academic_sessions",
        },
        {
            type: "link",
            txt: "Activation Keys",
            link: "/activation_keys",
        },

        {
            type: "link",
            txt: "Principal" + "'s Comments_Bank",
            link: "/principalComments",
        },

        {
            type: "link",
            txt: "Manual Principal" + "'s Comments",
            link: "/principalComment",
        },

        {
            type: "link",
            txt: " Auto Principal" + "'s Comments",
            link: "/comment",
        },

        {
            type: "link",
            txt: "Teacher" + "'s Comments",
            link: "/tutor_comments",
        },
        {
            type: "link",
            txt: "Comment Bank",
            link: "/teachers_comment",
        },
        {
            type: "link",
            txt: "Creche Domain",
            link: "/creche_domain",
        },

        {
            type: "link",
            txt: "Creche Ratings",
            link: "/creche_ratings",
        },

        {
            type: "link",
            txt: "Grading Group",
            link: "/grading_group",
        },
        {
            type: "link",
            txt: "Grading System",
            link: "/gradings",
        },
        {
            type: "link",
            txt: "Result Config",
            link: "/configs",
        },
        {
            type: "link",
            txt: "Learning Domain",
            link: "/learning_domain",
        },
    ],
    //superadmin
    superadmin: [
        {
            type: "title",
            txt: "Settings",
            icon: "fa fa-key blue context-menu__title-icon",
        },
        {
            type: "link",
            txt: "Schools",
            link: "/school",
        },

        {
            type: "link",
            txt: "Result Templates",
            link: "/templates",
        },

        {
            type: "link",
            txt: "Activation Keys",
            link: "/activation_keys",
        },

        {
            type: "link",
            txt: "Manage Payment API",
            link: "/payment_api",
        },
        {
            type: "link",
            txt: "Manage SMS",
            link: "/manage_sms",
        },
    ],
    account: [
        {
            type: "title",
            txt: "My Account",
            icon: "fa fa-user context-menu__title-icon",
        },

        {
            type: "link",
            txt: "Profile",
            link: "/staff_profile",
        },

        {
            type: "link",
            txt: "Logout",
            link: "/logout",
        },
    ],

    // student Account
    student_account: [
        {
            type: "title",
            txt: "My Account",
            icon: "fa fa-user context-menu__title-icon",
        },

        {
            type: "link",
            txt: "Profile",
            link: "/student_profile",
        },

        {
            type: "link",
            txt: "Logout",
            link: "/logout",
        },
    ],

    // CBT
    cbt: [
        {
            type: "title",
            txt: "CBT",
            icon: "fa fa-pencil context-menu__title-icon",
        },

        {
            type: "link",
            txt: "Schedul Exam",
            link: "/exams",
        },

        {
            type: "link",
            txt: "Exam List",
            link: "/exam_list",
        },
    ],

    // Chat Routs
    chat: [
        {
            type: "title",
            txt: "Chats",
            icon: "fa fa-coversation context-menu__title-icon",
        },

        {
            type: "link",
            txt: "Class chat",
            link: "/level_chats",
        },

        {
            type: "link",
            txt: "School Chat",
            link: "/chats",
        },

        {
            type: "link",
            txt: "SMS Sender",
            link: "/sms",
        },
        {
            type: "link",
            txt: "Email Sender",
            link: "/sms",
        },
    ],
    //TUTOR
    tutor: [
        {
            type: "title",
            txt: "Form Tutor",
            icon: "fa fa-pencil context-menu__title-icon",
        },


        {
            type: "link",
            txt: "Report Card",
            link: "/reports",
        },

        {
            type: "link",
            txt: "Score Card",
            link: "/scores",
        },


        {
            type: "link",
            txt: "Teacher" + "'s Comments",
            link: "/tutor_comments",
        },
        {
            type: "link",
            txt: "Creche" + "'s Comments",
            link: "/creche_comment",
        },

        {
            type: "link",
            txt: "Learning Domain",
            link: "/learning_domain_grading",
        },



        {
            type: "link",
            txt: "School Days",
            link: "/attendance",
        },


        {
            type: "link",
            txt: "Daily Attendance",
            link: "/daily/attendance",
        },
         {
            type: "link",
            txt: "Weekly Activity",
            link: "/weekly/marks",
        },

    ],



    tutorGenerals: [
      {
            type: "link",
            txt: "Students",
            link: "/students",
        },
        {

            type: "link",
            txt: "Daily Attendance",
            link: "/daily/attendance",
        },
         {
            type: "link",
            txt: "Assignment-Notes",
            link: "/assignments",
        },
              {
            type: "link",
            txt: "Videos",
            link: "/video_list",
        },
        {
            type: "link",
            txt: "Live Classes",
            link: "/liveClasses",
        },
          {
            type: "link",
            txt: "Subject Performance",
            link: "/subject_tracker",
        },
          {
            type: "link",
            txt: "Comment Bank",
            link: "/teachers_comment",
        },
           {
            type: "link",
            txt: "Backup Scores",
            link: "/backup",
        },

],


    fees: [
        {
            type: "title",
            txt: "Fee Management",
            icon: "fa fa-pencil context-menu__title-icon",
        },

        {
            type: "link",
            txt: "Fee Group",
            link: "/feegroup",
        },
        {
            type: "link",
            txt: "Fee Items",
            link: "/feeItems",
        },
        {
            type: "link",
            txt: "E-Wallet",
            link: "/ewallets",
        },

        {
            type: "link",
            txt: "Bills",
            link: "/bills",
        },
        {
            type: "link",
            txt: "Fee Query",
            link: "/feeQuery",
        },
        {
            type: "link",
            txt: "Payment List",
            link: "/payment-list",
        },
    ],

    Inventory: [
        {
            type: "title",
            txt: "Inventory",
            icon: "fa fa-book context-menu__title-icon",
        },

        {
            type: "link",
            txt: "Category",
            link: "/inventory/category",
        },
        {
            type: "link",
            txt: "Products",
            link: "/inventory/products",
        },

        {
            type: "link",
            txt: "Suppliers",
            link: "/inventory/suppliers",
        },
        {
            type: "link",
            txt: "Sales",
            link: "/inventory/sales",
        },
        {
            type: "link",
            txt: "Add Stock",
            link: "/inventory/purchases",
        },
        {
            type: "link",
            txt: "Stock",
            link: "/inventory/stock",
        },
        {
            type: "link",
            txt: "Manage Prices",
            link: "/inventory/prices",
        },
        {
            type: "link",
            txt: "Pettycash Group",
            link: "/petty/cash/groups",
        },
        {
            type: "link",
            txt: "Petty Cash",
            link: "/petty/cash",
        },
        {
            type: "link",
            txt: "Expense Book",
            link: "/petty/cash/expenses",
        },

        {
            type: "link",
            txt: "School Items",
            link: "/inventory/items",
        },
        {
            type: "link",
            txt: "Add Items",
            link: "/inventory/items/addItems",
        },
        {
            type: "link",
            txt: "Issue Items",
            link: "/inventory/items/issue",
        },
    ],

    // ADMISSION

    Admission: [
        {
            type: "title",
            txt: "Admission",
            icon: "fa fa-book context-menu__title-icon",
        },

        {
            type: "link",
            txt: "Candidate List",
            link: "/admission/candidates",
        },

        {
            type: "link",
            txt: "Personal Info",
            link: "/admission/profile",
        },

        {
            type: "link",
            txt: "Admission Requirements",
            link: "/admission/requirements",
        },

        {
            type: "link",
            txt: "CBT",
            link: "/admission/cbt",
        },
        {
            type: "link",
            txt: "Admission Status",
            link: "/admission/admission-status",
        },
    ],
    // DOWNLOADS
    Downloads: [
        {
            type: "title",
            txt: "Download/History",
            icon: "fa fa-history context-menu__title-icon",
        },
        {
            type: "link",
            txt: "Download Login Details",
            link: "/exports",
        },

        {
            type: "link",
            txt: "Upload  Zipe Images",
            link: "/photos",
        },
        {
            type: "link",
            txt: "History",
            link: "/history",
        },
    ],
};
