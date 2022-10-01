export default class Gate {
    constructor(user) {
        this.user = user;
    }

    isAdmin() {
        if (this.user.type === "admin" || this.user.type === "superadmin") {
            return true;
        }
    }
    isSuperadmin() {
        if (this.user.type === "superadmin") {
            return true;
        }
    }
    isUser() {
        return this.user.type === "user";
    }
    isAdminOrTutor() {
        if (
            this.user.type === "admin" ||
            this.user.type === "tutor" ||
            this.user.type === "subjectTeacher" ||
            this.user.type === "superadmin"
        ) {
            return true;
        }
    }
    isAdminOrStudent() {
        if (
            this.user.type === "admin" ||
            this.user.type === "student" ||
            this.user.type === "superadmin"
        ) {
            return true;
        }
    }
    isStudent() {
        if (this.user.type === "student") {
            return true;
        }
    }
    isTutor() {
        if (this.user.type === "subjectTeacher" || this.user.type === "tutor") {
            return true;
        }
    }

    isTutorOrAdmin() {
        if (
            this.user.type === "tutor" ||
            this.user.type === "admin" ||
            this.user.type === "superadmin"
        ) {
            return true;
        }
    }
    isAdminOrTutorOrStudent() {
        if (
            this.user.type === "admin" ||
            this.user.type === "tutor" ||
            this.user.type === "student" ||
            this.user.type === "subjectTeacher" ||
            this.user.type === "superadmin"
        ) {
            return true;
        }
    }

    isAdminOrTutorOrStudentOrParent() {
        if (
            this.user.type === "admin" ||
            this.user.type === "tutor" ||
            this.user.type === "parent" ||
            this.user.type === "student" ||
            this.user.type === "subjectTeacher" ||
            this.user.type === "superadmin"
        ) {
            return true;
        }
    }
    isSubjectOrFormTutor() {
        if (this.user.type === "subjectTeacher" || this.user.type === "tutor") {
            return true;
        }
    }

    isAdminOrSubjectTutor() {
        if (
            this.user.type === "subjectTeacher" ||
            this.user.type === "tutor" ||
            this.user.type === "admin" ||
            this.user.type === "superadmin"
        ) {
            return true;
        }
    }
    isActive() {
        if (this.user.isActive === 1) {
            return true;
        }
    }

    isActive() {
        if (this.user.isActive === 1) {
            return true;
        }
    }

    isAdminOrTutorOrParent() {
        if (
            this.user.type === "parent" ||
            this.user.type === "admin" ||
            this.user.type === "tutor" ||
            this.user.type === "superadmin"
        ) {
            return true;
        }
    }

    isStudentOrParent() {
        if (this.user.type === "parent" || this.user.type === "student") {
            return true;
        }
    }

    isParent() {
        if (this.user.type === "parent") {
            return true;
        }
    }

    isCandidate() {
        if (this.user.type === "candidate") {
            return true;
        }
    }
    isAdminOrCandidate() {
        if (
            this.user.type === "candidate" ||
            this.user.type === "admin" ||
            this.user.type === "superadmin"
        ) {
            return true;
        }
    }

    isOwnsMeeting(created_by) {
        if (this.user.staff_id && this.user.staff_id === parseInt(created_by))
            return true;
    }
}
