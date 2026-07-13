<?php
namespace App\Http\Controllers;

use App\Models\Parcel;
use Illuminate\Http\Request;

class ParcelController extends Controller
{
    // GET /parcels —— 查列表
    public function index()
    {
        return $this->success(Parcel::all());

    }

    // GET /parcels/{id} —— 查单个
    public function show($id)
    {
        $parcel = Parcel::findOrFail($id);
        return $this->success($id, 'Parcel found successfully');
    }

    // POST /parcels —— 新建
    public function store(Request $request)
    {
        // TODO: 校验 tracking_no / recipient_name / address 必填
        // 提示：$this->validate($request, [...]);
        $this->validate($request, [
            'tracking_no'    => 'required|string|max:64|unique:parcels,tracking_no',
            'recipient_name' => 'required|string|max:100',
            'address'        => 'required|string|max:255',
            'weight'         => 'nullable|numeric|min:0',
            'status'         => 'nullable|in:pending,in_transit,delivered',
        ]);
//        $this->validate($request, [
//            'tracking_no'    => 'required|string|max:64|unique:parcels,tracking_no',
//            'recipient_name' => 'required|string|max:100',
//            'address'        => 'required|string|max:255',
//            'weight'         => 'required|numeric|min:0',
//        ]);

        $parcel = Parcel::create($request->all());
        // TODO: 新建成功应该返回 200 还是 201？
        return $this->success($parcel, 'Parcel created successfully', 201);
    }

    // PUT /parcels/{id} —— 修改
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'tracking_no'    => 'sometimes|string|max:64|unique:parcels,tracking_no,' . $id,
            'recipient_name' => 'sometimes|string|max:100',
            'address'        => 'sometimes|string|max:255',
            'weight'         => 'sometimes|numeric|min:0',
            'status'         => 'sometimes|in:pending,in_transit,delivered',
        ]);
        $parcel = Parcel::find($id);
        // TODO: 找不到怎么办？
        if (!$parcel) {
            return response()->json([
                'message' => 'Parcel record not found'
            ], 404);
        }
        $parcel->update($request->all());
        return $this->success($parcel, 'Parcel updated successfully');
    }

    // DELETE /parcels/{id} —— 删除
    public function destroy($id)
    {
        // TODO: 删除并返回合适的状态码（删除成功通常返回 200 或 204）
        Parcel::destroy($id);
        return $this->success($id, 'Parcel removed successfully');
    }
}
