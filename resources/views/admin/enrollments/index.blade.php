@extends('layouts.dashboard')

@section('title', 'Manage Enrollments')

@section('content')
<!-- Add error/success messages -->
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="row">
    <div class="col-12">
        <!-- Add Enrollment Modal -->
        <div class="modal fade" id="addEnrollmentModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg">
                    <div class="modal-header bg-light">
                        <h5 class="modal-title fw-bold"><i class="fas fa-user-plus text-success me-2"></i>Add New Enrollment</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('admin.enrollments.store') }}" method="POST">
                        @csrf
                        <div class="modal-body p-4">
                            <div class="mb-4">
                                <label class="form-label fw-bold text-dark">Student ID</label>
                                <div class="position-relative">
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0" style="width: 40px; justify-content: center;">
                                            <i class="fas fa-id-card text-dark"></i>
                                        </span>
                                        <input type="text" id="student_id_input" class="form-control border-start-0 ps-3" 
                                               placeholder="Enter Student ID" autocomplete="off" required>
                                    </div>
                                    <div id="student_suggestions" class="position-absolute w-100 mt-1 shadow border rounded bg-white" 
                                         style="display:none; z-index:1000; max-height:200px; overflow-y:auto;"></div>
                                </div>
                                <input type="hidden" name="user_id" id="user_id_input">
                                <input type="hidden" id="student_course" value="">
                                <div id="student_details" class="mt-2 text-sm"></div>
                            </div>
                            
                            <div class="mb-4">
                                <label class="form-label fw-bold text-dark">Subject Code</label>
                                <div class="position-relative">
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0" style="width: 40px; justify-content: center;">
                                            <i class="fas fa-book text-dark"></i>
                                        </span>
                                        <input type="text" id="subject_code_input" class="form-control border-start-0 ps-3"
                                               placeholder="Enter Subject Code" autocomplete="off" required disabled>
                                    </div>
                                    <div id="subject_suggestions" class="position-absolute w-100 mt-1 shadow border rounded bg-white" 
                                         style="display:none; z-index:1000; max-height:200px; overflow-y:auto;"></div>
                                </div>
                                <input type="hidden" name="subject_id" id="subject_id_input">
                                <div id="subject_details" class="mt-2 text-sm"></div>
                            </div>
                            
                            <div class="mb-4">
                                <label class="form-label fw-bold text-dark">Academic Year</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0" style="width: 40px; justify-content: center;">
                                        <i class="fas fa-calendar-alt text-dark"></i>
                                    </span>
                                    <input type="text" id="academic_year_input" name="academic_year" class="form-control border-start-0 ps-3 bg-light" 
                                           readonly required>
                                </div>
                                <div class="text-muted small mt-1">
                                    <i class="fas fa-info-circle me-1"></i> Automatically set to current academic year
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold text-dark">Semester</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0" style="width: 40px; justify-content: center;">
                                        <i class="fas fa-university text-dark"></i>
                                    </span>
                                    <input type="text" id="semester_input" name="semester" class="form-control border-start-0 ps-3 bg-light"
                                           readonly required>
                                </div>
                                <div class="text-muted small mt-1">
                                    <i class="fas fa-info-circle me-1"></i> Automatically set to current semester
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer bg-light">
                            <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-success px-4">Enroll Student</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="card mb-4 shadow-sm">
            <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 py-1">Manage Enrollments</h6>
                <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addEnrollmentModal">
                    <i class="fas fa-plus me-1"></i> Add New Enrollment
                </button>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Student ID</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Name</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Subject</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Academic Year</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Semester</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($enrollments as $enrollment)
                            <tr>
                                <td>
                                    <p class="text-xs font-weight-bold mb-0 px-2 py-2">{{ $enrollment->student->student_id }}</p>
                                </td>
                                <td>
                                    <div class="d-flex px-2 py-2">
                                        <div class="d-flex flex-column justify-content-center">
                                            <h6 class="mb-0 text-sm">{{ $enrollment->student->name }}</h6>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <p class="text-xs font-weight-bold mb-0 px-2 py-2">{{ $enrollment->subject->code }} - {{ $enrollment->subject->name }}</p>
                                </td>
                                <td>
                                    <p class="text-xs font-weight-bold mb-0 px-2 py-2">{{ $enrollment->academic_year }}</p>
                                </td>
                                <td>
                                    <p class="text-xs font-weight-bold mb-0 px-2 py-2">{{ $enrollment->semester }}</p>
                                </td>
                                <td class="align-middle px-2 py-2">
                                    <form action="{{ route('admin.enrollments.destroy', $enrollment->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm rounded-1" onclick="return confirm('Are you sure you want to cancel this enrollment?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Set current academic year and semester automatically
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-determine current academic year and semester
        const currentDate = new Date();
        const currentMonth = currentDate.getMonth() + 1; // Months are 0-indexed
        const currentYear = currentDate.getFullYear();
        
        // Academic year calculation
        // If we're in June to December, it's the current year to next year
        // If we're in January to May, it's the previous year to current year
        let academicYear;
        if (currentMonth >= 6) {
            academicYear = `${currentYear}-${currentYear + 1}`;
        } else {
            academicYear = `${currentYear - 1}-${currentYear}`;
        }
        
        // Semester calculation
        // 1st semester: June to October
        // 2nd semester: November to May
        let semester;
        if (currentMonth >= 6 && currentMonth <= 10) {
            semester = '1st';
        } else {
            semester = '2nd';
        }
        
        // Set the values in the form
        document.getElementById('academic_year_input').value = academicYear;
        document.getElementById('semester_input').value = semester;

        // Reset form fields when modal is hidden
        const modal = document.getElementById('addEnrollmentModal');
        modal.addEventListener('hidden.bs.modal', function () {
            document.getElementById('student_id_input').value = '';
            document.getElementById('student_details').innerHTML = '';
            document.getElementById('user_id_input').value = '';
            document.getElementById('student_course').value = '';
            document.getElementById('subject_code_input').value = '';
            document.getElementById('subject_details').innerHTML = '';
            document.getElementById('subject_id_input').value = '';
            document.getElementById('subject_code_input').disabled = true;
        });
    });

    function updateStudentDetails(student) {
        if (student) {
            document.getElementById('student_details').innerHTML = 
                `<div class="card border-0 shadow-sm mb-0 mt-2">
                    <div class="card-body p-3 bg-gray-100">
                        <h6 class="mb-2 text-dark"><i class="fas fa-user-graduate me-2"></i>Student Information</h6>
                        <div class="mb-1"><strong>Name:</strong> ${student.name}</div>
                        <div class="mb-1"><strong>Course:</strong> ${student.course}</div>
                        <div class="mb-0"><strong>Year Level:</strong> ${student.year_level}</div>
                    </div>
                </div>`;
            document.getElementById('user_id_input').value = student.id;
            document.getElementById('student_id_input').value = student.student_id;
            document.getElementById('student_course').value = student.course;
            document.getElementById('student_suggestions').style.display = 'none';
            
            // Enable subject input after student is selected
            document.getElementById('subject_code_input').disabled = false;
            document.getElementById('subject_code_input').focus();
            
            // Clear any existing subject data
            document.getElementById('subject_code_input').value = '';
            document.getElementById('subject_details').innerHTML = '';
            document.getElementById('subject_id_input').value = '';
        }
    }

    function updateSubjectDetails(subject) {
        if (subject) {
            document.getElementById('subject_details').innerHTML = 
                `<div class="card border-0 shadow-sm mb-0 mt-2">
                    <div class="card-body p-3 bg-gray-100">
                        <h6 class="mb-2 text-dark"><i class="fas fa-book-open me-2"></i>Subject Information</h6>
                        <div class="mb-1"><strong>Name:</strong> ${subject.name}</div>
                        <div class="mb-1"><strong>Units:</strong> ${subject.units}</div>
                        <div class="mb-1"><strong>Course:</strong> ${subject.course}</div>
                        <div class="mb-0"><strong>Year Level:</strong> ${subject.year_level}</div>
                    </div>
                </div>`;
            document.getElementById('subject_id_input').value = subject.id;
            document.getElementById('subject_code_input').value = subject.code;
            document.getElementById('subject_suggestions').style.display = 'none';
        }
    }

    let searchTimeout;
    
    document.getElementById('student_id_input').addEventListener('input', function() {
        const searchTerm = this.value.trim();
        const suggestionsDiv = document.getElementById('student_suggestions');
        
        clearTimeout(searchTimeout);
        
        if (!searchTerm) {
            suggestionsDiv.style.display = 'none';
            document.getElementById('student_details').innerHTML = '';
            document.getElementById('user_id_input').value = '';
            document.getElementById('student_course').value = '';
            return;
        }

        searchTimeout = setTimeout(() => {
            // Show loading indicator
            suggestionsDiv.innerHTML = '<div class="p-2 text-center"><div class="spinner-border spinner-border-sm text-secondary" role="status"></div> Searching...</div>';
            suggestionsDiv.style.display = 'block';
            
            fetch(`/admin/search-students/${searchTerm}`)
                .then(response => response.json())
                .then(students => {
                    if (students.length > 0) {
                        suggestionsDiv.innerHTML = students.map(student => `
                            <div class="p-3 border-bottom cursor-pointer hover-bg-light" 
                                 onclick="updateStudentDetails(${JSON.stringify(student).replace(/"/g, '&quot;')})">
                                <div class="d-flex justify-content-between">
                                    <strong>${student.student_id}</strong>
                                    <span class="badge bg-secondary text-white">${student.course}</span>
                                </div>
                                <div>${student.name}</div>
                            </div>
                        `).join('');
                        suggestionsDiv.style.display = 'block';
                    } else {
                        suggestionsDiv.innerHTML = '<div class="p-3 text-center text-muted">No students found</div>';
                    }
                })
                .catch(err => {
                    suggestionsDiv.innerHTML = '<div class="p-3 text-center text-danger">Error searching students</div>';
                });
        }, 300);
    });

    document.getElementById('subject_code_input').addEventListener('input', function() {
        const searchTerm = this.value.trim().toUpperCase();
        const suggestionsDiv = document.getElementById('subject_suggestions');
        const studentCourse = document.getElementById('student_course').value;
        
        clearTimeout(searchTimeout);
        
        if (!searchTerm) {
            suggestionsDiv.style.display = 'none';
            document.getElementById('subject_details').innerHTML = '';
            document.getElementById('subject_id_input').value = '';
            return;
        }

        if (!studentCourse) {
            suggestionsDiv.innerHTML = '<div class="p-3 text-center text-warning">Please select a student first</div>';
            suggestionsDiv.style.display = 'block';
            return;
        }

        searchTimeout = setTimeout(() => {
            // Show loading indicator
            suggestionsDiv.innerHTML = '<div class="p-2 text-center"><div class="spinner-border spinner-border-sm text-secondary" role="status"></div> Searching...</div>';
            suggestionsDiv.style.display = 'block';
            
            // Use the existing search-subjects route but filter client-side
            fetch(`/admin/search-subjects/${searchTerm}`)
                .then(response => response.json())
                .then(subjects => {
                    // Filter subjects to match student's course
                    const matchingSubjects = subjects.filter(subject => subject.course === studentCourse);
                    
                    if (matchingSubjects.length > 0) {
                        suggestionsDiv.innerHTML = matchingSubjects.map(subject => `
                            <div class="p-3 border-bottom cursor-pointer hover-bg-light" 
                                 onclick="updateSubjectDetails(${JSON.stringify(subject).replace(/"/g, '&quot;')})">
                                <div class="d-flex justify-content-between">
                                    <strong>${subject.code}</strong>
                                    <span class="badge bg-dark text-white">${subject.units} units</span>
                                </div>
                                <div>${subject.name}</div>
                            </div>
                        `).join('');
                        suggestionsDiv.style.display = 'block';
                    } else {
                        suggestionsDiv.innerHTML = `
                            <div class="p-3 text-center text-muted">
                                No subjects found for ${studentCourse} program
                            </div>`;
                    }
                })
                .catch(err => {
                    suggestionsDiv.innerHTML = '<div class="p-3 text-center text-danger">Error searching subjects</div>';
                });
        }, 300);
    });

    // Close suggestions when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('#student_id_input')) {
            document.getElementById('student_suggestions').style.display = 'none';
        }
        if (!e.target.closest('#subject_code_input')) {
            document.getElementById('subject_suggestions').style.display = 'none';
        }
    });
</script>

<style>
.cursor-pointer { cursor: pointer; }
.hover-bg-light:hover { background-color: #f8f9fa; }
.bg-gray-100 { background-color: #f8f9fa; }
.text-xs { font-size: 0.75rem; }
.text-sm { font-size: 0.875rem; }
</style>
@endpush