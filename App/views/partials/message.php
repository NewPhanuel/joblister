<?php if (isset($_SESSION['success_message'])): ?>
    <div class="message py-2 px-4 my-3 bg-green-100 rounded" style="color:rgb(16, 118, 0)">
        <?= $_SESSION['success_message'] ?>
    </div>
    <?php unset($_SESSION['success_message']) ?>
<?php endif; ?>
<?php if (isset($_SESSION['error_message'])): ?>
    <div class="message py-2 px-4 my-3 bg-red-100 rounded" style="color:rgb(187, 43, 43)">
        <?= $_SESSION['error_message'] ?>
    </div>
    <?php unset($_SESSION['error_message']) ?>
<?php endif; ?>