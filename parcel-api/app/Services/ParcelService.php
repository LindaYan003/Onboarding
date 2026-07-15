<?php
namespace App\Services;

use App\Models\Parcel;
use Illuminate\Support\Facades\Log;
use App\Repositories\ParcelRepository;
use Carbon\Carbon;
class ParcelService
{
    public function __construct(private ParcelRepository $repo) {}
    //   ← Dependency Injection again: Service asks for Repository,
    //     container provides it. Service never calls Eloquent directly.

    public function getAll()
    {
        return $this->repo->all();
    }

    public function findOrFail(int $id): Parcel
    {
        return $this->repo->findOrFail($id);
    }

    public function create(array $data): Parcel
    {
        $data['status'] = 'pending';
        $parcel = $this->repo->create($data);

        Log::info('Parcel created', [
            'parcel_id'   => $parcel->id,
            'tracking_no' => $parcel->tracking_no,
        ]);

        return $parcel;
    }

    public function update(Parcel $parcel, array $data): Parcel
    {
        $old_status = $parcel->status;
        $updated = $this->repo->update($parcel, $data);

        if (isset($data['status']) && $data['status'] !== $old_status) {
            Log::info('Parcel status changed', [
                'parcel_id' => $parcel->id,
                'from'      => $old_status,
                'to'        => $data['status'],
            ]);
        }

        return $updated;
    }

    public function softDelete(Parcel $parcel): void
    {
        $this->repo->softDelete($parcel);

        Log::info('Parcel soft deleted', [
            'parcel_id'  => $parcel->id,
            'deleted_at' => Carbon::now()->toDateTimeString(),
        ]);
    }

    // Bonus: expose deleted parcels for customer service
    public function getDeleted()
    {
        return $this->repo->deleted();
    }
}
