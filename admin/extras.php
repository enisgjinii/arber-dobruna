<?php
// admin/extras.php
require_once 'includes/db_connect.php';
require_once 'includes/header.php';

// Initialize variables
$action = $_GET['action'] ?? 'view';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$message = '';

// Handle different actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($action === 'add') {
        // Add Extra
        $name = trim($_POST['name']);
        $price = trim($_POST['price']);
        $category = trim($_POST['category']) ?: 'addon';

        // Validate inputs
        if ($name === '' || $price === '') {
            $message = '<div class="alert alert-danger">Name and Price are required.</div>';
        } elseif (!is_numeric($price) || floatval($price) < 0) {
            $message = '<div class="alert alert-danger">Price must be a valid non-negative number.</div>';
        } elseif ($category === '') {
            $message = '<div class="alert alert-danger">Category is required.</div>';
        } else {
            try {
                $stmt = $pdo->prepare("INSERT INTO `extras` (`name`, `price`, `category`) VALUES (?, ?, ?)");
                $stmt->execute([$name, $price, $category]);
                $message = '<div class="alert alert-success">Extra added successfully.</div>';
                // Redirect to view
                header("Location: extras.php");
                exit();
            } catch (PDOException $e) {
                if ($e->getCode() === '23000') { // Integrity constraint violation
                    $message = '<div class="alert alert-danger">Extra name already exists.</div>';
                } else {
                    $message = '<div class="alert alert-danger">Error: ' . htmlspecialchars($e->getMessage()) . '</div>';
                }
            }
        }
    } elseif ($action === 'edit' && $id > 0) {
        // Edit Extra
        $name = trim($_POST['name']);
        $price = trim($_POST['price']);
        $category = trim($_POST['category']) ?: 'addon';

        // Validate inputs
        if ($name === '' || $price === '') {
            $message = '<div class="alert alert-danger">Name and Price are required.</div>';
        } elseif (!is_numeric($price) || floatval($price) < 0) {
            $message = '<div class="alert alert-danger">Price must be a valid non-negative number.</div>';
        } elseif ($category === '') {
            $message = '<div class="alert alert-danger">Category is required.</div>';
        } else {
            try {
                $stmt = $pdo->prepare("UPDATE `extras` SET `name` = ?, `price` = ?, `category` = ? WHERE `id` = ?");
                $stmt->execute([$name, $price, $category, $id]);
                $message = '<div class="alert alert-success">Extra updated successfully.</div>';
                // Redirect to view
                header("Location: extras.php");
                exit();
            } catch (PDOException $e) {
                if ($e->getCode() === '23000') { // Integrity constraint violation
                    $message = '<div class="alert alert-danger">Extra name already exists.</div>';
                } else {
                    $message = '<div class="alert alert-danger">Error: ' . htmlspecialchars($e->getMessage()) . '</div>';
                }
            }
        }
    }
} elseif ($action === 'delete' && $id > 0) {
    // Delete Extra
    try {
        $stmt = $pdo->prepare("DELETE FROM `extras` WHERE `id` = ?");
        $stmt->execute([$id]);
        $message = '<div class="alert alert-success">Extra deleted successfully.</div>';
        // Redirect to view
        header("Location: extras.php");
        exit();
    } catch (PDOException $e) {
        $message = '<div class="alert alert-danger">Error: ' . htmlspecialchars($e->getMessage()) . '</div>';
    }
}

// Fetch all extras for viewing
if ($action === 'view') {
    try {
        $stmt = $pdo->query("SELECT * FROM `extras` ORDER BY `created_at` DESC");
        $extras = $stmt->fetchAll();
    } catch (PDOException $e) {
        $message = '<div class="alert alert-danger">Error fetching extras: ' . htmlspecialchars($e->getMessage()) . '</div>';
    }
}
?>

<?php if ($action === 'view'): ?>
    <h2>Manage Extras</h2>
    <?php if ($message): ?>
        <?= $message ?>
    <?php endif; ?>

    <!-- Add Extra Button -->
    <a href="extras.php?action=add" class="btn btn-primary mb-3">Add Extra</a>

    <!-- Extras Table -->
    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Price (€)</th>
                <th>Category</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($extras)): ?>
                <?php foreach ($extras as $extra): ?>
                    <tr>
                        <td><?= htmlspecialchars($extra['id']) ?></td>
                        <td><?= htmlspecialchars($extra['name']) ?></td>
                        <td><?= number_format($extra['price'], 2) ?></td>
                        <td><?= htmlspecialchars($extra['category']) ?></td>
                        <td><?= htmlspecialchars($extra['created_at']) ?></td>
                        <td>
                            <a href="extras.php?action=edit&id=<?= $extra['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                            <a href="#" class="btn btn-sm btn-danger" onclick="showDeleteModal(<?= $extra['id'] ?>)">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="text-center">No extras found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="GET" action="extras.php">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="id" id="deleteExtraId">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete this extra?
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger">Delete</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        function showDeleteModal(id) {
            document.getElementById('deleteExtraId').value = id;
            var deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
            deleteModal.show();
        }
    </script>

<?php elseif ($action === 'add' || ($action === 'edit' && $id > 0)): ?>
    <?php
    if ($action === 'edit') {
        // Fetch existing extra details
        try {
            $stmt = $pdo->prepare("SELECT * FROM `extras` WHERE `id` = ?");
            $stmt->execute([$id]);
            $extra = $stmt->fetch();
            if (!$extra) {
                echo '<div class="alert alert-danger">Extra not found.</div>';
                require_once 'includes/footer.php';
                exit();
            }
        } catch (PDOException $e) {
            echo '<div class="alert alert-danger">Error fetching extra details: ' . htmlspecialchars($e->getMessage()) . '</div>';
            require_once 'includes/footer.php';
            exit();
        }
    }
    ?>

    <h2><?= $action === 'add' ? 'Add Extra' : 'Edit Extra' ?></h2>
    <?php if ($message): ?>
        <?= $message ?>
    <?php endif; ?>
    <form method="POST" action="extras.php?action=<?= $action ?><?= $action === 'edit' ? '&id=' . $id : '' ?>">
        <?php if ($action === 'edit'): ?>
            <!-- Hidden input for ID -->
            <input type="hidden" name="id" value="<?= $id ?>">
        <?php endif; ?>
        <div class="mb-3">
            <label for="name" class="form-label">Extra Name *</label>
            <input type="text" class="form-control" id="name" name="name" required value="<?= $action === 'edit' ? htmlspecialchars($extra['name']) : (isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '') ?>">
        </div>
        <div class="mb-3">
            <label for="price" class="form-label">Price (€) *</label>
            <input type="number" step="0.01" min="0" class="form-control" id="price" name="price" required value="<?= $action === 'edit' ? htmlspecialchars($extra['price']) : (isset($_POST['price']) ? htmlspecialchars($_POST['price']) : '') ?>">
        </div>
        <div class="mb-3">
            <label for="category" class="form-label">Category *</label>
            <input type="text" class="form-control" id="category" name="category" required value="<?= $action === 'edit' ? htmlspecialchars($extra['category']) : (isset($_POST['category']) ? htmlspecialchars($_POST['category']) : 'addon') ?>">
            <div class="form-text">Default is 'addon'.</div>
        </div>
        <button type="submit" class="btn btn-success"><?= $action === 'add' ? 'Add' : 'Update' ?> Extra</button>
        <a href="extras.php" class="btn btn-secondary">Cancel</a>
    </form>

<?php endif; ?>

<?php
require_once 'includes/footer.php';
?>