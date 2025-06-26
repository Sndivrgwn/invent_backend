    <!-- script tailwind -->
    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if(session('toast'))
            showToast("{{ session('toast.message') }}", "{{ session('toast.type') }}");
            @endif

            @if($errors -> any())
            @foreach($errors -> all() as $error)
            showToast("{{ $error }}", "error");
            @endforeach
            @endif
        });

       // Update your showToast function to reset the toastShown flag after a delay
function showToast(message, type) {
    // Prevent duplicate toasts
    if (window.toastShown) return;
    window.toastShown = true;

    Toastify({
        text: message,
        duration: 3000,
        close: true,
        gravity: "top",
        position: "right",
        backgroundColor: type === 'success' ? '#4CAF50' : '#F44336',
        stopOnFocus: true,
    }).showToast();

    // Reset the flag after the toast duration
    setTimeout(() => {
        window.toastShown = false;
    }, 3000);
}

// Update your handleAjaxResponse function
function handleAjaxResponse(response, successMessage = null) {
    if (response.toast) {
        showToast(response.toast.message, response.toast.type);
    } else if (successMessage) {
        showToast(successMessage, 'success');
    }
    
    if (response.redirect) {
        window.location.href = response.redirect;
    } else if (response.reload) {
        window.location.reload();
    }
}

    </script>

    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script type="module" src="https://unpkg.com/cally"></script>

    </body>

    </html>
