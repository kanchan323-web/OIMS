  </div>
    <!-- Wrapper End-->
    {{-- <footer class="iq-footer">
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
    </footer> --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sessionLifetime = 1800; // 30 minutes
            const warningTime = 180; // Warn 3 minutes before
            // const sessionLifetime = 30; // 30 minutes
            // const warningTime = 10; // Warn 3 minutes before
            let countdownInterval;
            let timeoutWarning, timeoutRedirect;
        
            function resetTimers() {
                clearTimeout(timeoutWarning);
                clearTimeout(timeoutRedirect);
                
                timeoutWarning = setTimeout(showWarning, (sessionLifetime - warningTime) * 1000);
                timeoutRedirect = setTimeout(() => logout(true), sessionLifetime * 1000);
            }
        
            function showWarning() {
                // Create modal div
                const modal = document.createElement('div');
                modal.id = 'session-timeout-warning';
                modal.className = 'modal fade show';
                modal.style.display = 'block';
                modal.style.backgroundColor = 'rgba(0,0,0,0.5)';
                modal.setAttribute('role', 'dialog');
                
                // Initial remaining time
                let remainingSeconds = warningTime;
                
                // Modal dialog with countdown display
                modal.innerHTML = `
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header bg-warning">
                            <h5 class="modal-title text-dark">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                Session About to Expire
                            </h5>
                        </div>
                        <div class="modal-body">
                            <div class="text-center mb-3">
                                <div class="countdown-circle">
                                    <svg width="100" height="100" viewBox="0 0 100 100">
                                        <circle cx="50" cy="50" r="45" fill="none" stroke="#e9ecef" stroke-width="8"/>
                                        <circle id="countdown-path" cx="50" cy="50" r="45" fill="none" 
                                                stroke="#ffc107" stroke-width="8" stroke-linecap="round"
                                                transform="rotate(-90 50 50)"/>
                                    </svg>
                                    <div class="countdown-text">
                                        <span id="countdown-display">${formatTime(remainingSeconds)}</span>
                                    </div>
                                </div>
                            </div>
                            <p class="text-center">Your session will expire due to inactivity.</p>
                            <p class="text-center">Would you like to continue your session?</p>
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
                document.body.style.paddingRight = '15px';
                
                // Start countdown animation
                const countdownPath = document.getElementById('countdown-path');
                const countdownDisplay = document.getElementById('countdown-display');
                const circumference = 2 * Math.PI * 45;
                countdownPath.style.strokeDasharray = circumference;
                
                countdownInterval = setInterval(() => {
                    remainingSeconds--;
                    countdownDisplay.textContent = formatTime(remainingSeconds);
                    
                    // Update progress circle
                    const offset = circumference - (remainingSeconds / warningTime) * circumference;
                    countdownPath.style.strokeDashoffset = offset;
                    
                    if (remainingSeconds <= 0) {
                        clearInterval(countdownInterval);
                        logout(true);
                        removeModal();
                    }
                }, 1000);
                
                // Add event listeners
                document.getElementById('session-extend').addEventListener('click', function() {
                    clearInterval(countdownInterval);
                    extendSession();
                    removeModal();
                });
                
                document.getElementById('session-logout').addEventListener('click', function() {
                    clearInterval(countdownInterval);
                    logout();
                    removeModal();
                });
                
                // Close modal when clicking backdrop
                modal.addEventListener('click', function(e) {
                    if (e.target === modal) {
                        clearInterval(countdownInterval);
                        removeModal();
                        resetTimers();
                    }
                });
                
                function removeModal() {
                    if (document.body.contains(modal)) {
                        document.body.removeChild(modal);
                        document.body.classList.remove('modal-open');
                        document.body.style.overflow = '';
                        document.body.style.paddingRight = '';
                    }
                }
            }
        
            function formatTime(seconds) {
                const mins = Math.floor(seconds / 60);
                const secs = seconds % 60;
                return `${mins}:${secs < 10 ? '0' : ''}${secs}`;
            }
        
            function extendSession() {
                const path = window.location.pathname;
                const isAdmin = path.includes('/admin/');
                const extendUrl = isAdmin ? "{{ route('admin.extend-session') }}" 
                                        : "{{ route('user.extend-session') }}";
                
                fetch(extendUrl, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                }).then(() => resetTimers());
            }
        
            function logout(isTimeout = false) {
                const path = window.location.pathname;
                let loginRoute;
                
                if (path.includes('/OIMS/admin/')) {
                    loginRoute = "{{ route('admin.login') }}";
                } else if (path.includes('/OIMS/user/')) {
                    loginRoute = "{{ route('user.login') }}";
                } else {
                    loginRoute = "{{ route('user.login') }}";
                }
                
                window.location.href = isTimeout ? `${loginRoute}?timeout=1` : loginRoute;
            }
        
            // Reset on activity
            ['click', 'mousemove', 'keypress', 'scroll'].forEach(evt => {
                window.addEventListener(evt, resetTimers, { passive: true });
            });
        
            resetTimers(); // Initialize
        });
        </script>
        
        <style>
        .countdown-circle {
            position: relative;
            width: 100px;
            height: 100px;
            margin: 0 auto 15px;
        }
        .countdown-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 1.5rem;
            font-weight: bold;
        }
        .modal {
            z-index: 1060;
        }
        .modal-open {
            overflow: hidden;
        }
        </style>
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