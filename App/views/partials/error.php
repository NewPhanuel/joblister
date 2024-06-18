<?php if (isset($errors)): ?>
    <?php foreach ($errors as $error): ?>
        <div class="message bg-red-100 my-3 px-2 rounded py-2 error" style="color:rgb(187, 43, 43)">
            <?= $error ?>
        </div>
    <?php endforeach; ?>
<?php endif; ?>