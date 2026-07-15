<?php
namespace App\Repositories;

use App\Models\Parcel;
use Illuminate\Database\Eloquent\Collection;

class ParcelRepository
{
    // Returns only non-deleted parcels
    public function all(): Collection
    {
        return Parcel::all();
        // SoftDeletes trait makes this automatically exclude deleted_at IS NOT NULL
    }

    // Returns only non-deleted. Throws ModelNotFoundException for deleted or missing.
    public function findOrFail(int $id): Parcel
    {
        return Parcel::findOrFail($id);
    }

    public function create(array $data): Parcel
    {
        return Parcel::create($data);
    }

    public function update(Parcel $parcel, array $data): Parcel
    {
        $parcel->update($data);
        return $parcel->fresh();
    }

    // Soft delete — sets deleted_at, does NOT run DELETE
    public function softDelete(Parcel $parcel): void
    {
        $parcel->delete();   // Because SoftDeletes trait is on the model,
        // this sets deleted_at, not a hard delete
    }

    // Bonus: query only deleted parcels (for customer service lookup)
    public function deleted(): Collection
    {
        return Parcel::onlyTrashed()->get();
    }
}
