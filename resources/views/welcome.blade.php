<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Training Platform Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <div class="container mt-5">
        <h2 class="text-center">Training Platform Management</h2>

        <!-- Navigation Tabs -->
        <ul class="nav nav-tabs mt-3">
            <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#students">Students</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#courses">Courses</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#schedules">Training Schedules</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#optin">Student Opt-in/Out</a></li>
        </ul>

        <div class="tab-content mt-4">
            
            <!-- Student Management -->
            <div id="students" class="tab-pane fade show active">
                <h4>Add New Student</h4>
                <form id="studentForm">
                    <div class="mb-2"><label>Name</label><input type="text" class="form-control" id="name" required></div>
                    <div class="mb-2"><label>Course</label><input type="text" class="form-control" id="course" required></div>
                    <div class="mb-2"><label>Email</label><input type="email" class="form-control" id="email" required></div>
                    <div class="mb-2"><label>Phone</label><input type="text" class="form-control" id="phone" required></div>
                    <button type="submit" class="btn btn-primary">Add Student</button>
                </form>

                <h4 class="mt-4">Student List</h4>
                <table class="table table-bordered">
                    <thead class="table-dark">
                        <tr><th>ID</th><th>Name</th><th>Course</th><th>Email</th><th>Phone</th><th>Actions</th></tr>
                    </thead>
                    <tbody id="studentTable"></tbody>
                </table>
            </div>

            <!-- Course Management -->
            <div id="courses" class="tab-pane fade">
                <h4>Add New Course</h4>
                <form id="courseForm">
                    <div class="mb-2"><label>Course Name</label><input type="text" class="form-control" id="courseName" required></div>
                    <button type="submit" class="btn btn-primary">Add Course</button>
                </form>

                <h4 class="mt-4">Course List</h4>
                <table class="table table-bordered">
                    <thead class="table-dark">
                        <tr><th>ID</th><th>Course Name</th><th>Actions</th></tr>
                    </thead>
                    <tbody id="courseTable"></tbody>
                </table>
            </div>

            <!-- Training Schedule -->
            <div id="schedules" class="tab-pane fade">
                <h4>Add Training Schedule</h4>
                <form id="scheduleForm">
                    <div class="mb-2"><label>Training Name</label><input type="text" class="form-control" id="trainingName" required></div>
                    <div class="mb-2"><label>Date</label><input type="date" class="form-control" id="trainingDate" required></div>
                    <button type="submit" class="btn btn-primary">Add Schedule</button>
                </form>

                <h4 class="mt-4">Training Schedule</h4>
                <table class="table table-bordered">
                    <thead class="table-dark">
                        <tr><th>ID</th><th>Training Name</th><th>Date</th><th>Actions</th></tr>
                    </thead>
                    <tbody id="scheduleTable"></tbody>
                </table>
            </div>

            <!-- Student Opt-in/Out -->
            <div id="optin" class="tab-pane fade">
                <h4>Student Opt-in/Out</h4>
                <form id="optinForm">
                    <div class="mb-2"><label>Student ID</label><input type="text" class="form-control" id="studentID" required></div>
                    <div class="mb-2"><label>Training ID</label><input type="text" class="form-control" id="trainingID" required></div>
                    <div class="mb-2">
                        <label>Opt-in Status</label>
                        <select class="form-control" id="optinStatus">
                            <option value="opt-in">Opt-in</option>
                            <option value="opt-out">Opt-out</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Update Status</button>
                </form>

                <h4 class="mt-4">Opt-in/Out List</h4>
                <table class="table table-bordered">
                    <thead class="table-dark">
                        <tr><th>Student ID</th><th>Training ID</th><th>Status</th><th>Actions</th></tr>
                    </thead>
                    <tbody id="optinTable"></tbody>
                </table>
            </div>

        </div>
    </div>

    <script>
        const studentAPI = "http://127.0.0.1:8000/api/students";
        const courseAPI = "http://127.0.0.1:8000/api/courses";
        const scheduleAPI = "http://127.0.0.1:8000/api/schedules";
        const optinAPI = "http://127.0.0.1:8000/api/optin";

        function fetchData(apiUrl, tableId, formatRow) {
            fetch(apiUrl)
                .then(response => response.json())
                .then(data => {
                    const tableBody = document.getElementById(tableId);
                    tableBody.innerHTML = "";
                    if (data.status === 200) {
                        data.data.forEach(item => {
                            tableBody.innerHTML += formatRow(item);
                        });
                    }
                })
                .catch(error => console.log("Error fetching data:", error));
        }

        document.getElementById("studentForm").addEventListener("submit", function(event) {
            event.preventDefault();
            const name = document.getElementById("name").value;
            const course = document.getElementById("course").value;
            const email = document.getElementById("email").value;
            const phone = document.getElementById("phone").value;

            fetch(studentAPI, {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ name, course, email, phone })
            })
            .then(response => response.json())
            .then(() => fetchData(studentAPI, "studentTable", studentRow))
            .catch(error => console.log("Error adding student:", error));
        });

        function studentRow(student) {
            return `<tr>
                        <td>${student.id}</td>
                        <td>${student.name}</td>
                        <td>${student.course}</td>
                        <td>${student.email}</td>
                        <td>${student.phone}</td>
                        <td><button class="btn btn-warning btn-sm">Edit</button>
                            <button class="btn btn-danger btn-sm">Delete</button></td>
                    </tr>`;
        }

        fetchData(studentAPI, "studentTable", studentRow);
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
