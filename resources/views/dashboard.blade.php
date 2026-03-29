@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="fw-bold text-dark mb-4">My Dashboard</h2>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="card border-primary bg-primary bg-opacity-10 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title text-primary fw-semibold">Total Sessions</h5>
                    <h2 class="card-text fw-bold text-primary mb-0">{{ $totalSessions }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-success bg-success bg-opacity-10 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title text-success fw-semibold">Total Minutes Meditated</h5>
                    <h2 class="card-text fw-bold text-success mb-0">{{ $totalMinutes }} <small class="fs-6 text-muted">mins</small></h2>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card shadow-sm border-0 text-center py-4 bg-dark text-white rounded-3">
                <h4 class="fw-bold mb-3 text-light"><i class="fas fa-stopwatch me-2"></i>Live Meditation Timer</h4>
                
                <div class="display-1 fw-bold font-monospace mb-4 text-info" id="timerDisplay">00:00</div>
                
                <div>
                    <button id="startTimerBtn" class="btn btn-success btn-lg px-5 me-2 rounded-pill fw-bold shadow">Start Focus</button>
                    <button id="stopTimerBtn" class="btn btn-danger btn-lg px-5 rounded-pill fw-bold shadow d-none">Stop & Log Session</button>
                </div>
                <small class="text-white-50 mt-3 d-block" id="timerMessage">Click Start when you are ready to begin.</small>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
                    <h5 class="fw-bold text-secondary">Log Manual Session</h5>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger py-2">{{ $errors->first() }}</div>
                    @endif

                    <form action="{{ route('sessions.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-medium">Date</label>
                            <input type="date" name="session_date" class="form-control" value="{{ date('Y-m-d') }}" required max="{{ date('Y-m-d') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-medium">Duration (Minutes)</label>
                            <input type="number" name="duration_minutes" class="form-control" placeholder="e.g. 15" required min="1">
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-medium">Notes (Optional)</label>
                            <textarea name="notes" class="form-control" rows="3" placeholder="How did you feel?"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 fw-bold">Save Session</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
                    <h5 class="fw-bold text-secondary">Meditation History</h5>
                </div>
                <div class="card-body">
                    @if($sessions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Date</th>
                                        <th>Duration</th>
                                        <th>Notes</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($sessions as $session)
                                    <tr>
                                        <td class="fw-medium">{{ \Carbon\Carbon::parse($session->session_date)->format('M d, Y') }}</td>
                                        <td><span class="badge bg-info text-dark">{{ $session->duration_minutes }} mins</span></td>
                                        <td class="text-muted small">{{ $session->notes ?? '-' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5 text-muted">
                            <p>You haven't logged any sessions yet. Take a deep breath and start today!</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let timerInterval;
    let secondsElapsed = 0;
    let isRunning = false;

    const display = document.getElementById('timerDisplay');
    const startBtn = document.getElementById('startTimerBtn');
    const stopBtn = document.getElementById('stopTimerBtn');
    const message = document.getElementById('timerMessage');

    function updateDisplay() {
        const minutes = String(Math.floor(secondsElapsed / 60)).padStart(2, '0');
        const seconds = String(secondsElapsed % 60).padStart(2, '0');
        display.innerText = `${minutes}:${seconds}`;
    }

    startBtn.addEventListener('click', () => {
        if (isRunning) return;
        isRunning = true;
        
        startBtn.classList.add('d-none');
        stopBtn.classList.remove('d-none');
        message.innerText = "Breathe in, breathe out... Timer is running.";
        message.classList.add('text-warning');
        
        timerInterval = setInterval(() => {
            secondsElapsed++;
            updateDisplay();
        }, 1000); 
    });

    stopBtn.addEventListener('click', () => {
        clearInterval(timerInterval);
        isRunning = false;
        
        // to convert seconds in mints (min 1 limit)
        let durationMinutes = Math.floor(secondsElapsed / 60);
        if (durationMinutes < 1) durationMinutes = 1; 

        message.innerText = "Saving your session securely...";
        message.classList.replace('text-warning', 'text-info');

        fetch("{{ route('sessions.store') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                "X-Requested-With": "XMLHttpRequest" 
            },
            body: JSON.stringify({
                session_date: new Date().toISOString().split('T')[0], 
                duration_minutes: durationMinutes,
                notes: "Logged automatically via Live Timer"
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                message.innerText = "Awesome! Session logged successfully. Refreshing...";
                message.classList.replace('text-info', 'text-success');
                setTimeout(() => window.location.reload(), 1500); 
            }
        })
        .catch(error => {
            console.error("Error:", error);
            message.innerText = "Oops! Something went wrong.";
            message.classList.replace('text-info', 'text-danger');
        });

        startBtn.classList.remove('d-none');
        stopBtn.classList.add('d-none');
    });
</script>
@endsection 