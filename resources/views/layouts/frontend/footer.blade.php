  </div>
    <!-- Wrapper End-->
    <footer class="iq-footer">
            <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <ul class="list-inline mb-0">
                                <li class="list-inline-item"><a href="../backend/privacy-policy.html">Privacy Policy</a></li>
                                <li class="list-inline-item"><a href="../backend/terms-of-service.html">Terms of Use</a></li>
                            </ul>
                        </div>
                        <div class="col-lg-6 text-right">
                            <span class="mr-1"><script>document.write(new Date().getFullYear())</script>©</span> <a href="#" class="">OIMS</a>.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
<script>
    document.addEventListener('DOMContentLoaded', function() {
    const sessionLifetime = 300; // 5 minute
    const warningTime = 60; // Warn 1 min before
    
    let timeoutWarning, timeoutRedirect;

    function resetTimers() {
        clearTimeout(timeoutWarning);
        clearTimeout(timeoutRedirect);
        
        timeoutWarning = setTimeout(showWarning, (sessionLifetime - warningTime) * 1000);
        timeoutRedirect = setTimeout(logout, sessionLifetime * 1000);
    }

    function showWarning() {
    // Create modal div
    const modal = document.createElement('div');
    modal.id = 'session-timeout-warning';
    modal.className = 'modal fade show';
    modal.style.display = 'block';
    modal.style.backgroundColor = 'rgba(0,0,0,0.5)';
    modal.setAttribute('role', 'dialog');
    
    // Modal dialog
    modal.innerHTML = `
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title text-dark"><i class="fas fa-exclamation-triangle me-2"></i>Session About to Expire</h5>
            </div>
            <div class="modal-body">
                <p>Your session will expire in ${warningTime} seconds due to inactivity.</p>
                <p>Would you like to continue your session?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="session-logout">Log Out</button>
                <button type="button" class="btn btn-primary" id="session-extend">Continue Session</button>
            </div>
        </div>
    </div>
    `;
    
    // Add to body
    document.body.appendChild(modal);
    document.body.classList.add('modal-open');
    document.body.style.overflow = 'hidden';
    document.body.style.paddingRight = '15px'; // Prevent scrollbar jump
    
    // Add event listeners
    document.getElementById('session-extend').addEventListener('click', function() {
        extendSession();
        removeModal();
    });
    
    document.getElementById('session-logout').addEventListener('click', function() {
        logout();
        removeModal();
    });
    
    // Close modal when clicking backdrop
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            removeModal();
        }
    });
    
    function removeModal() {
        document.body.removeChild(modal);
        document.body.classList.remove('modal-open');
        document.body.style.overflow = '';
        document.body.style.paddingRight = '';
    }
}

    function extendSession() {
        fetch("{{ route('admin.extend-session') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        }).then(() => resetTimers());
    }

    function logout() {
    const isAdminPage = window.location.pathname.startsWith('/admin');
    const loginRoute = isAdminPage ? "{{ route('admin.login') }}" : "{{ route('user.login') }}";
    window.location.href = `${loginRoute}?timeout=1`;
}

    // Reset on activity
    ['click', 'mousemove', 'keypress'].forEach(evt => {
        window.addEventListener(evt, resetTimers);
    });

    resetTimers(); // Initialize
});
</script>
    <!-- Backend Bundle JavaScript -->
    <script src="{{ asset('resources/js/backend-bundle.min.js') }}"></script>

    <!-- Table Treeview JavaScript -->
    <script src="{{ asset('resources/js/table-treeview.js') }}"></script>

    <!-- Chart Custom JavaScript -->
    <script src="{{ asset('resources/js/customizer.js') }}"></script>

    <!-- Chart Custom JavaScript -->
    <script async src="{{ asset('resources/js/chart-custom.js') }}"></script>

    <!-- app JavaScript -->
    <script src="{{ asset('resources/js/app.js') }}"></script>
  </body>
</html>
