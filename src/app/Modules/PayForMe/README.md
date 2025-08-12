# PayForMe Module

This module provides Pay-For-Me service allowing users to request foreign payments.

## Local enable

Add `App\Modules\PayForMe\PayForMeServiceProvider::class` to the `ModulesServiceProvider` providers array locally (do not commit).

Run migrations:

```
php artisan migrate --path=app/Modules/PayForMe/Database/Migrations --realpath
```

Access via `/modules/payforme/request`.

Configure quote stub via `config('payforme.quote.stub')` or tinker.
