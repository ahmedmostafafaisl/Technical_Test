<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ApiResponseHelper;
use App\Http\Requests\Task\Date_filterTaskRequest;
use App\Http\Requests\Task\SortTaskRequest;
use App\Http\Requests\Task\Status_filterTaskRequest;
use App\Http\Requests\Task\StoreTaskRequest;
use App\Models\Task;
use Illuminate\Support\Facades\Validator;
use PhpParser\Node\Expr\Cast\String_;

class TaskController extends Controller
{
    use ApiResponseHelper;

    public function index(Request $request)
    {
        $tasks = Task::orderBy('created_at', 'DESC')->where('id', '>', 0);
        $per_page = (int) ($request->per_page ?? 10);
        $total_items = $tasks->count();
        $tasks = $tasks->paginate($per_page);

        if ($total_items) {
            return  $this->setCode(200)->setData(['tasks' => $tasks->items(), 'current_page' => $tasks->currentPage()])->setMessage('Success')->send();
        }
        return  $this->setCode(200)->setData(['tasks' => []])->setMessage('There is no tasks Added Yet')->send();
    }


    public function store(StoreTaskRequest $request)
    {
        $task = Task::create($request->validated());
        return  $this->setCode(200)->setData($task)->setMessage('success')->send();
    }


    public function show(String $id)
    {
        $task = Task::where('id', $id)->first();
        if ($task) {
            return  $this->setCode(200)->setData($task)->setMessage('success')->send();
        }
        return  $this->setCode(200)->setData(['tasks' => []])->setMessage('The task is not found')->send();
    }


    public function update(StoreTaskRequest $request, string $id)
    {
        $task = Task::where('id', $id)->first();
        if ($task) {
            $task->update($request->validated());
            return  $this->setCode(200)->setData($task)->setMessage('Task Updated Successfully')->send();
        }
        return  $this->setCode(200)->setData(['tasks' => []])->setMessage('The task is not found')->send();
    }


    public function destroy(string $id)
    {
        $task = Task::where('id', $id)->first();
        if ($task) {
            $task->delete();
            return  $this->setCode(200)->setData([])->setMessage('Task Deleted Successfully')->send();
        }
        return  $this->setCode(200)->setData(['tasks' => []])->setMessage('The task is not found')->send();
    }


    public function filter_status(Status_filterTaskRequest $request)
    {

        $status_values = ['in_progress', 'completed', 'pending'];
        if (!in_array($request->status, $status_values)) {
            return response()->json(['error' => 'status not found', 'code' => 404], 422);
        }
        $tasks = Task::where('status', '=', $request->status)->get();

        if ($tasks->count()) {
            return  $this->setCode(200)->setData($tasks)->setMessage('Success')->send();
        }
        return  $this->setCode(200)->setData(['tasks' => []])->setMessage('There is no tasks with ' . $request->status . ' status')->send();
    }


    public function filter_date_creation(Date_filterTaskRequest $request)
    {
        $tasks = Task::whereBetween('due_date', [$request->start, $request->end])->get();

        if ($tasks->count()) {
            return  $this->setCode(200)->setData($tasks)->setMessage('Success')->send();
        }
        return  $this->setCode(200)->setData(['tasks' => []])->setMessage('There is no tasks in this date')->send();
    }

    public function sort_task(SortTaskRequest $request)
    {
        $sort_by = ['date', 'status'];
        if (!in_array($request->key, $sort_by)) {
            return response()->json(['error' => 'sort not found', 'code' => 404], 422);
        }
        if ($request->key == 'date') {
            $tasks = Task::orderBy('due_date')->get();
        } elseif ($request->key == 'status') {
            $tasks = Task::orderBy('status')->get();
        }
        if ($tasks->count()) {
            return  $this->setCode(200)->setData($tasks)->setMessage('Success')->send();
        }
        return  $this->setCode(200)->setData(['tasks' => []])->setMessage('There is no tasks in this date')->send();
    }
}
