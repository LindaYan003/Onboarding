# Parcel Validation — Changes

Added request validation to `store()` and `update()` in `ParcelController`, replacing the earlier TODO placeholders.

## `store()` — POST `/parcels`

```php
$this->validate($request, [
    'tracking_no'    => 'required|string|max:64|unique:parcels,tracking_no',
    'recipient_name' => 'required|string|max:100',
    'address'        => 'required|string|max:255',
    'weight'         => 'nullable|numeric|min:0',
    'status'         => 'nullable|in:pending,in_transit,delivered',
]);
```

| Field             | Rules                                                  |
|-------------------|----------------------------------------------------------|
| `tracking_no`     | required, string, max 64 chars, must be unique in `parcels` table |
| `recipient_name`  | required, string, max 100 chars                          |
| `address`         | required, string, max 255 chars                          |
| `weight`          | optional (`nullable`), numeric, minimum 0                |
| `status`          | optional (`nullable`), must be one of: `pending`, `in_transit`, `delivered` |

If validation fails, Laravel automatically returns a `422` response with per-field error messages.

## `update()` — PUT `/parcels/{id}`

```php
$this->validate($request, [
    'tracking_no'    => 'sometimes|string|max:64|unique:parcels,tracking_no,' . $id,
    'recipient_name' => 'sometimes|string|max:100',
    'address'        => 'sometimes|string|max:255',
    'weight'         => 'sometimes|numeric|min:0',
    'status'         => 'sometimes|in:pending,in_transit,delivered',
]);
```

| Field             | Rules                                                     |
|-------------------|--------------------------------------------------------------|
| `tracking_no`     | `sometimes` (only validated if present), string, max 64 chars, unique excluding the current record's own ID |
| `recipient_name`  | `sometimes`, string, max 100 chars                            |
| `address`         | `sometimes`, string, max 255 chars                            |
| `weight`          | `sometimes`, numeric, minimum 0                               |
| `status`          | `sometimes`, must be one of: `pending`, `in_transit`, `delivered` |

`sometimes` means a field is only validated if it's actually included in the request body — this allows partial updates without requiring every field to be resent.

## Why `required` vs `sometimes`

- `store()` uses `required` for the three core fields (`tracking_no`, `recipient_name`, `address`) because a new parcel can't meaningfully exist without them.
- `update()` uses `sometimes` for everything, since a PUT request may only be changing one or two fields (e.g. just `status`) and shouldn't be forced to resend the entire payload.

## Unique constraint on `tracking_no`

- On create: `unique:parcels,tracking_no` — no two parcels can share a tracking number.
- On update: `unique:parcels,tracking_no,{id}` — same uniqueness check, but excludes the current record so you can save an update without the parcel's own existing tracking number triggering a false "duplicate" error.

## Removed

A commented-out, earlier draft of the `store()` validation block (with `weight` as `required` instead of `nullable`, and no `status` rule) was left in the file — safe to delete now that the active validation block is in place, unless you want to keep it for reference.
