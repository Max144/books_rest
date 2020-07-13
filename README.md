<p align="center">
    API for managing books, authors and categories, documentation using swagger
</p>
<p align="center">
    <h3> Deployment </h3>
    <ul>
        <li>composer install</li>
        <li>make and fill .env</li>
        <li>php artisan key:generate</li>
        <li>php artisan migrate --seed</li>
        <li>php artisan passport:install</li>
        <li>php artisan l5-swagger:generate</li>
    </ul>
</p>

<p align="center">
    <h3> Swagger </h3>
    <ul>
        <li>Documentation url - /api/documentation</li>
        <li>first you should use login route (credentials - admin@admin.com / admin) to get token in response header</li>
        <li>authorize in swagger with token from previous step</li>
        <li>Now you can use authorized routes!</li>
    </ul>
</p>
