<?php

$components = ['Dashboard', 'Keuangan', 'Goals', 'Statistik', 'Settings', 'Login'];

foreach ($components as $name) {
    $lower = strtolower($name);
    
    // PHP Class
    $phpContent = "<?php\n\nnamespace App\\Livewire;\n\nuse Livewire\\Component;\n\nclass {$name} extends Component\n{\n    public function render()\n    {\n        return view('livewire.{$lower}');\n    }\n}\n";
    file_put_contents(__DIR__ . "/app/Livewire/{$name}.php", $phpContent);

    // Blade View
    $bladeContent = "<div>\n    <h1>{$name} Page</h1>\n</div>\n";
    file_put_contents(__DIR__ . "/resources/views/livewire/{$lower}.blade.php", $bladeContent);
}

echo "Components created successfully.\n";
