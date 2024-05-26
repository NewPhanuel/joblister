<?php
declare(strict_types=1);

/**
 * Get the base path
 *
 * @param string $path
 * @return string
 */
function basePath(string $path): string
{
    return __DIR__ . '/' . $path;
}

/**
 * Load a view
 *
 * @param string ...$name
 * @return void
 */
function loadView(string ...$names): void
{
    foreach ($names as $name) {
        $viewPath = basePath("views/{$name}.view.php");

        if (file_exists($viewPath)) {
            require $viewPath;
        } else {
            echo "View {$name} not found!";
        }
    }
}

/**
 * Load a partial
 *
 * @param string ...$partials
 * @return void
 */
function loadPartial(string ...$partials): void
{
    foreach ($partials as $partial) {
        $partialPath = basePath("views/partials/{$partial}.php");

        if (file_exists($partialPath)) {
            require $partialPath;
        } else {
            echo "Partial {$partial} not found!";
        }
    }
}

/**
 * Prints out formatted values of any variable
 *
 * @param mixed $object
 * @return void
 */
function inspect(mixed $object): void
{
    echo '<pre>';
    print_r($object);
    echo '</pre><br />';
}

/**
 * Prints out formatted values of any variable and kills the script
 *
 * @param mixed $object
 * @return void
 */
function inspectAndDie(mixed $object): void
{
    echo '<pre>';
    print_r($object);
    echo '</pre>';
    die();
}