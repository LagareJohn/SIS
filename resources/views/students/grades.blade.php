@extends('layouts.dashboard')

@section('title', 'Grade Report')

@section('content')
<!-- Header -->
<div class="card bg-gradient-success mb-4">
  <div class="card-body p-4">
    <div class="row align-items-center">
      <div class="col-md-8">
        <h3 class="text-white mb-0">Academic Grade Report</h3>
        <p class="text-white opacity-8 mb-0">View your academic performance across all semesters</p>
      </div>
      <div class="col-md-4 text-end">
        <div class="d-flex justify-content-end">
          <a href="{{ route('dashboard') }}" class="btn btn-white btn-sm me-2">Back to Dashboard</a>
          <button onclick="window.print()" class="btn btn-outline-white btn-sm">
            <i class="fas fa-print me-2"></i> Print
          </button>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Filter Controls -->
<div class="card mb-4">
  <div class="card-body p-3">
    <form action="{{ route('grades') }}" method="GET" class="row align-items-center">
      <div class="col-md-4">
        <label class="form-label mb-0">Academic Year</label>
        <select name="academic_year" class="form-control form-control-sm" onchange="this.form.submit()">
          @foreach($academicYears as $year)
            <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>
              {{ $year }}
            </option>
          @endforeach
        </select>
      </div>
      <div class="col-md-4">
        <label class="form-label mb-0">Semester</label>
        <select name="semester" class="form-control form-control-sm" onchange="this.form.submit()">
          @foreach($semesters as $semester)
            <option value="{{ $semester }}" {{ $selectedSemester == $semester ? 'selected' : '' }}>
              {{ $semester }} Semester
            </option>
          @endforeach
        </select>
      </div>
      <div class="col-md-4 d-flex align-items-center justify-content-end">
        <div class="d-flex align-items-center">
          <div class="me-3">
            <span class="text-sm mb-0">Semester GWA:</span>
            <span class="badge bg-gradient-success ms-1">{{ $gwa }}</span>
          </div>
          <div>
            <span class="text-sm mb-0">Cumulative GWA:</span>
            <span class="badge bg-gradient-info ms-1">{{ $cumulativeGwa }}</span>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Grade Analytics -->
<div class="row mb-4">
  <div class="col-lg-12"> <!-- Changed from col-lg-8 to col-lg-12 for full width -->
    <div class="card h-100">
      <div class="card-header pb-0">
        <h6>Grade Distribution</h6>
      </div>
      <div class="card-body p-3">
        <div class="chart">
          <canvas id="grade-distribution-chart" class="chart-canvas" height="400"></canvas> <!-- Increased height from 300 to 400 -->
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Grades Table -->
<div class="card">
  <div class="card-header p-3">
    <div class="row">
      <div class="col-md-6">
        <h6 class="mb-0">{{ $selectedYear }} | {{ $selectedSemester }} Semester</h6>
      </div>
      <div class="col-md-6 text-end">
        <div class="input-group input-group-sm w-50 ms-auto">
          <span class="input-group-text"><i class="fas fa-search"></i></span>
          <input type="text" id="gradeSearch" class="form-control" placeholder="Search subjects...">
        </div>
      </div>
    </div>
  </div>
  
  <div class="table-responsive">
    <table class="table align-items-center mb-0" id="gradesTable">
      <thead>
        <tr>
          <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Code</th>
          <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Name (Subject)</th>
          <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Grade</th>
          <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Units</th>
          <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Remark</th>
        </tr>
      </thead>
      <tbody>
        @forelse($grades as $grade)
        <tr>
          <td>
            <div class="d-flex px-2 py-1">
              <div class="d-flex flex-column justify-content-center">
                <h6 class="mb-0 text-xs {{ $grade->final_grade == '5.00' ? 'text-danger' : '' }}
                                        {{ $grade->final_grade == 'INC' ? 'text-warning' : '' }}
                                        {{ $grade->final_grade == 'DRP' ? 'text-secondary' : '' }}">
                  {{ $grade->code }}
                </h6>
              </div>
            </div>
          </td>
          <td>
            <div class="d-flex px-2 py-1">
              <div class="d-flex flex-column justify-content-center">
                <h6 class="mb-0 text-xs {{ $grade->final_grade == '5.00' ? 'text-danger' : '' }}
                                        {{ $grade->final_grade == 'INC' ? 'text-warning' : '' }}
                                        {{ $grade->final_grade == 'DRP' ? 'text-secondary' : '' }}">
                  {{ $grade->name }}
                </h6>
              </div>
            </div>
          </td>
          <td class="align-middle text-center">
            @if(in_array($grade->final_grade, ['1.00', '1.25', '1.50']))
              <span class="badge bg-gradient-success">{{ $grade->final_grade }}</span>
            @elseif(in_array($grade->final_grade, ['1.75', '2.00', '2.25']))
              <span class="badge bg-gradient-info">{{ $grade->final_grade }}</span>
            @elseif(in_array($grade->final_grade, ['2.50', '2.75', '3.00']))
              <span class="badge bg-gradient-warning">{{ $grade->final_grade }}</span>
            @elseif($grade->final_grade == '5.00')
              <span class="badge bg-gradient-danger">{{ $grade->final_grade }}</span>
            @elseif($grade->final_grade == 'INC')
              <span class="badge bg-gradient-warning">{{ $grade->final_grade }}</span>
            @elseif($grade->final_grade == 'DRP')
              <span class="badge bg-gradient-secondary">{{ $grade->final_grade }}</span>
            @else
              <span class="badge bg-gradient-secondary">{{ $grade->final_grade }}</span>
            @endif
          </td>
          <td class="align-middle text-center">
            <span class="text-secondary text-xs font-weight-bold">{{ $grade->units }}</span>
          </td>
          <td class="align-middle text-center">
            @if(in_array($grade->final_grade, ['1.00', '1.25', '1.50', '1.75', '2.00', '2.25', '2.50', '2.75', '3.00']))
              <span class="badge badge-sm bg-gradient-success">Passed</span>
            @elseif($grade->final_grade == '5.00')
              <span class="badge badge-sm bg-gradient-danger">Failed</span>
            @elseif($grade->final_grade == 'INC')
              <span class="badge badge-sm bg-gradient-warning">Incomplete</span>
            @elseif($grade->final_grade == 'DRP')
              <span class="badge badge-sm bg-gradient-secondary">Dropped</span>
            @else
              <span class="badge badge-sm bg-gradient-secondary">Unknown</span>
            @endif
          </td>
        </tr>
        @empty
          <!-- No grades yet, show pending subjects -->
          @if($pendingGrades->isEmpty())
          <tr>
            <td colspan="5" class="text-center py-4">
              <div class="d-flex flex-column align-items-center">
                <i class="fas fa-file-alt fa-3x text-secondary mb-2"></i>
                <h6 class="text-secondary">No grades available for this semester</h6>
                <p class="text-xs text-secondary">Select a different academic year or semester</p>
              </div>
            </td>
          </tr>
          @endif
        @endforelse
        
        <!-- Show pending grades -->
        @foreach($pendingGrades as $enrollment)
        <tr>
          <td>
            <div class="d-flex px-2 py-1">
              <div class="d-flex flex-column justify-content-center">
                <h6 class="mb-0 text-xs text-secondary">{{ $enrollment->subject->code }}</h6>
              </div>
            </div>
          </td>
          <td>
            <div class="d-flex px-2 py-1">
              <div class="d-flex flex-column justify-content-center">
                <h6 class="mb-0 text-xs text-secondary">{{ $enrollment->subject->name }}</h6>
              </div>
            </div>
          </td>
          <td class="align-middle text-center">
            <span class="badge bg-gradient-light text-secondary">Pending</span>
          </td>
          <td class="align-middle text-center">
            <span class="text-secondary text-xs font-weight-bold">{{ $enrollment->subject->units }}</span>
          </td>
          <td class="align-middle text-center">
            <span class="badge badge-sm bg-gradient-light text-secondary">Ongoing</span>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  // Simple grade search functionality
  document.getElementById('gradeSearch').addEventListener('keyup', function() {
    const searchTerm = this.value.toLowerCase();
    const table = document.getElementById('gradesTable');
    const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
    
    for (let i = 0; i < rows.length; i++) {
      const code = rows[i].getElementsByTagName('td')[0]?.textContent.toLowerCase() || '';
      const name = rows[i].getElementsByTagName('td')[1]?.textContent.toLowerCase() || '';
      
      if (code.includes(searchTerm) || name.includes(searchTerm)) {
        rows[i].style.display = '';
      } else {
        rows[i].style.display = 'none';
      }
    }
  });
  
  // Grade distribution chart
  var ctx = document.getElementById('grade-distribution-chart');
  if (ctx) {
    ctx = ctx.getContext('2d');
    
    const gradeLabels = Object.keys({!! json_encode($gradeDistribution) !!});
    const gradeValues = Object.values({!! json_encode($gradeDistribution) !!});
    
    new Chart(ctx, {
      type: 'bar',
      data: {
        labels: gradeLabels,
        datasets: [{
          label: 'Number of Subjects',
          data: gradeValues,
          backgroundColor: [
            'rgba(66, 186, 150, 0.8)',  // Green for Excellent
            'rgba(66, 153, 225, 0.8)',  // Blue for Very Good
            'rgba(236, 201, 75, 0.8)',  // Yellow for Good
            'rgba(245, 54, 92, 0.8)',   // Red for Failed
            'rgba(160, 174, 192, 0.8)'  // Gray for Others
          ],
          borderWidth: 0
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: false
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              precision: 0
            }
          }
        },
        layout: {
          padding: {
            left: 20,
            right: 20,
            top: 20,
            bottom: 20
          }
        }
      }
    });
  }
});
</script>

<script>
// Prevent duplicate declarations
if (typeof iconNavbarSidenav === 'undefined') {
    var iconNavbarSidenav;
    var iconSidenav;
    var sidenav;
    var iconRTL;
    var sidenavToggler;
    var referenceButtons;
}
</script>

<style>
@media print {
  .navbar, .sidenav, .fixed-plugin, .card-header, form, .input-group, button, .btn {
    display: none !important;
  }
  
  .main-content {
    margin-left: 0 !important;
    padding: 0 !important;
  }
  
  .container-fluid {
    padding: 0 !important;
  }
  
  .card {
    box-shadow: none !important;
    border: 1px solid #eee !important;
  }
}
</style>
@endpush
