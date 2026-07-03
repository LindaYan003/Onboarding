<?php
namespace App\Http\Controllers;

use App\Models\Parcel;
use Illuminate\Http\Request;

class ParcelController extends Controller
{
    // GET /parcels —— 查列表
    public function index()
    {
        return response()->json(Parcel::all());
    }

    // GET /parcels/{id} —— 查单个
    public function show($id)
    {
        $parcel = Parcel::find($id);
        // TODO: 如果 $parcel 是 null（找不到），应该返回什么状态码？
        if($parcel == null){
            return response()->json([
                'message' => 'Parcel not found'
            ], 404);
        }
        return response()->json($parcel);
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
            'weight'         => 'required|numeric|min:0',
        ]);

        $parcel = Parcel::create($request->all());
        // TODO: 新建成功应该返回 200 还是 201？
        return response()->json($parcel, 201);
    }

    // PUT /parcels/{id} —— 修改
    public function update(Request $request, $id)
    {
        $parcel = Parcel::find($id);
        // TODO: 找不到怎么办？
        if (!$parcel) {
            return response()->json([
                'message' => 'Parcel record not found'
            ], 404);
        }
        $parcel->update($request->all());
        return response()->json($parcel);
    }

    // DELETE /parcels/{id} —— 删除
    public function destroy($id)
    {
        // TODO: 删除并返回合适的状态码（删除成功通常返回 200 或 204）
        Parcel::destroy($id);
        return response()->json(null, 204);
    }
}