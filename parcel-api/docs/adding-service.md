# Adding ParcelService

## What changed

- Added `App\Services\ParcelService`, which now owns all `Parcel` business logic
  (list, find-or-fail, create, update, delete) that previously lived directly
  in `ParcelController`.
- `ParcelController` no longer talks to the `Parcel` model directly. It gets
  `ParcelService` injected via the constructor and delegates each action to it.
- `store`/`update`/`destroy` now go through the service's `create`/`update`/`delete`
  methods instead of calling `Parcel::create`/`update`/`destroy` inline.
- `show` and `destroy` use `findOrFail`, so a missing parcel now results in the
  framework's standard 404 (via `ModelNotFoundException`) instead of a manual
  `if (!$parcel)` check.
- Removed dead/commented-out code and leftover Chinese TODO comments from the
  controller.

## Why

- Moves persistence and business rules (e.g. new parcels always start as
  `pending`, status changes get logged) out of the controller and into a
  single, testable place.
- Adds structured logging (`Log::info`) for parcel creation, status changes,
  and deletion, which is useful for auditing and debugging.
- Makes the controller a thin HTTP layer: validate input, call the service,
  return a response.