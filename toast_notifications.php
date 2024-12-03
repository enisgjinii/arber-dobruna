<?php
// Toast notifications configuration
$toastNotifications = [
    'added' => ['message' => 'Product added to cart successfully!', 'type' => 'success'],
    'removed' => ['message' => 'Product removed from cart.', 'type' => 'warning'],
    'updated' => ['message' => 'Cart updated successfully!', 'type' => 'info'],
];

// Display toast notifications
foreach ($toastNotifications as $key => $config) {
    if (isset($_GET[$key]) && $_GET[$key] == 1) {
        echo '<div class="toast-container position-fixed bottom-0 end-0 p-3">
                <div class="toast align-items-center text-bg-' . $config['type'] . ' border-0 show" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body">' . $config['message'] . '</div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>
              </div>';
    }
}
