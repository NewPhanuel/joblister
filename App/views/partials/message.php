<?php use Framework\Session; ?>

<?php $successMessage = Session::getFlash('success_message'); ?>
<?php if ($successMessage !== null): ?>
    <div class="message py-2 px-4 my-3 bg-green-100 rounded" style="color:rgb(16, 118, 0)">
        <?= $successMessage ?>
    </div>
<?php endif; ?>

<?php $errorMessage = Session::getFlash('error_message'); ?>
<?php if ($errorMessage !== null): ?>
    <div class="message py-2 px-4 my-3 bg-red-100 rounded" style="color:rgb(187, 43, 43)">
        <?= $errorMessage ?>
    </div>
<?php endif; ?>