<?php

namespace App\Http\Controllers;

use App\Models\User;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();

        return response()->json($users, 200);
    }
    public function tree()
    {
        $users = DB::select(DB::raw(
            'SELECT u.* ,(SELECT count(*)
               FROM users ui
               WHERE u.id = ui.user_id) AS indications
            FROM users u
            ORDER BY user_id DESC, created_at DESC, id DESC')
        );

        $arrUserPoints = [];
        $arrCountIndications = [];
        foreach ($users as $user) {
            if ($user->user_id > 0) {
                if (!isset($arrUserPoints[$user->user_id])) {
                    $arrUserPoints[$user->user_id] = [
                        'left' => 0,
                        'right' => 0
                    ];
                }
                $arrCountIndications[$user->user_id] = isset($arrCountIndications[$user->user_id]) ? $arrCountIndications[$user->user_id] : 0;

                if ($arrCountIndications[$user->user_id] === 0) {
                    $arrUserPoints[$user->user_id]['right'] += $user->points;
                } else {
                    $arrUserPoints[$user->user_id]['left'] += $user->points;
                }

                if ($user->indications > 0) {
                    if ($arrCountIndications[$user->user_id] === 0) {
                        $arrUserPoints[$user->user_id]['right'] += ($arrUserPoints[$user->id]['left'] + $arrUserPoints[$user->id]['right']);
                    } else {
                        $arrUserPoints[$user->user_id]['left'] += ($arrUserPoints[$user->id]['left'] + $arrUserPoints[$user->id]['right']);
                    }
                }

                $arrCountIndications[$user->user_id]++;
            } else {
                continue;
            }
        }

        return response()->json([
            'users' => $users,
            'tree_users_points' => $arrUserPoints
        ], 200);
    }

    public function indications()
    {
        $users = DB::select(DB::raw(
            'SELECT u.* ,(SELECT count(*)
               FROM users ui
               WHERE u.id = ui.user_id) AS indications
            FROM users u')
        );

        return response()->json($users, 200);
    }

    public function allowed_indicate()
    {
        $users = DB::select(DB::raw(
            'SELECT * ,(SELECT count(*)
               FROM users uu
               WHERE u.id = uu.user_id) AS indications
            FROM users u
            HAVING indications < 2')
        );

        return response()->json($users, 200);
    }

    public function show($id)
    {
        $user = User::where('id', '=', $id)
                            ->with('indicated_by')
                            ->with('indicated')
                            ->first();

        return ($user) ? $user : response()->json(['message' => 'User not found'], 404);
    }

    public function store(Request $request)
    {
        $validator = Validator::make(request()->all(), [
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'user_id' => 'required|integer',
            'points' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return response($validator->messages()->toArray(), 400);
        }

        $checkIndication = DB::select(DB::raw(
            'SELECT * ,(SELECT count(*)
               FROM users uu
               WHERE u.id = uu.user_id) AS indications
            FROM users u
            WHERE id = :id'),
            array('id' => $request->user_id)
        );

        foreach($checkIndication as $indication) {
            if ($indication->indications >= 2) {
                return response()->json(['message' => 'This user can not indicate other users'], 400);
            }
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'user_id' => $request->user_id,
            'points' => $request->points
        ]);

        return response()->json([
            'message' => 'New user created',
            'user' => $user
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if ($user) {
            $validator = Validator::make(request()->all(), [
                'name' => 'required|max:255',
                'email' => 'required|email|unique:users',
                'user_id' => 'required|integer',
                'points' => 'required|numeric'
            ]);

            if ($validator->fails()) {
                return response($validator->messages()->toArray(), 400);
            }

            $checkIndication = DB::select(DB::raw(
                'SELECT * ,(SELECT count(*)
                   FROM users uu
                   WHERE u.id = uu.user_id) AS indications
                FROM users u
                WHERE id = :id'),
                array('id' => $request->user_id)
            );

            foreach($checkIndication as $indication) {
                if ($indication->indications >= 2) {
                    return response()->json(['message' => 'This user can not indicate other users'], 400);
                }
            }

            $user->update($request->all());

            return response()->json(['message' => 'User updated successfully'], 200);
        } else {
            return response()->json(['message' => 'User not found'], 404);
        }
    }
}
