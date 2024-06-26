<?php use Framework\Session; ?>
<!-- Nav -->
<header class="bg-blue-900 text-white p-4">
    <div class="container mx-auto flex justify-between items-center">
        <div style="display:flex; align-items: center">
            <a href="/"><img src="/images/joblister.png"
                    style="margin-right: .4rem; width: 2rem; height: 2rem; display:inline-block; vertical-align:middle">
            </a>
            <h1 class="text-3xl font-semibold">
                <a href="/">Joblister</a>
            </h1>
        </div>
        <nav class="space-x-4">
            <?php if (Session::has('user')): ?>
                <div class="flex justify-between items-center gap-4">
                    <div style="color:rgb(140, 188, 255)">Welcome <?= Session::get('user')['name'] ?></div>
                    <form method="POST" action="/auth/logout">
                        <button type="submit" class="text-white hover:underline">Logout</button>
                    </form>
                    <a href="/listings/create"
                        class="bg-yellow-500 hover:bg-yellow-600 text-black px-4 py-2 rounded hover:shadow-md transition duration-300"><i
                            class="fa fa-edit"></i> Post a Job</a>
                </div>
            <?php else: ?>
                <a href="/auth/login" class="text-white hover:underline">Login</a>
                <a href="/auth/register" class="text-white hover:underline">Register</a>
            <?php endif; ?>
        </nav>
    </div>
</header>