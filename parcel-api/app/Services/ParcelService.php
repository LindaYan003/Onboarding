<?php
namespace App\Services;

use App\Models\Parcel;
use Illuminate\Support\Facades\Log;

class ParcelService
{
    public function getAll()
    {
        return Parcel::all();
    }

    public function findOrFail(int $id): Parcel
    {
        return Parcel::findOrFail($id);  // throws ModelNotFoundException if not found
    }

    public function create(array $data): Parcel
    {
        // Business rule: status is always 'pending' on creation
        $data['status'] = 'pending';

        $parcel = Parcel::create($data);

        // Business event log — info level: this is a normal, expected event
        Log::info('Parcel created', [
            'parcel_id'   => $parcel->id,
            'tracking_no' => $parcel->tracking_no,
        ]);

        return $parcel;
    }

    public function update(Parcel $parcel, array $data): Parcel
    {
        $old_status = $parcel->status;
        $parcel->update($data);

        // Log status changes explicitly — useful for auditing
        if (isset($data['status']) && $data['status'] !== $old_status) {
            Log::info('Parcel status changed', [
                'parcel_id' => $parcel->id,
                'from'      => $old_status,
                'to'        => $data['status'],
            ]);
        }

        return $parcel->fresh();
    }

    public function delete(Parcel $parcel): void
    {
        $parcel->delete();
        Log::info('Parcel deleted', ['parcel_id' => $parcel->id]);
    }
}
