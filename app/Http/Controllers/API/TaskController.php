<?php
   
namespace App\Http\Controllers\API;
   
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Resources\Task as TaskResource;
use App\Models\Task;
use Validator;
use Config;
use URL;

class TaskController extends BaseController
{

    public function index()
    {
        $tasks = Task::all();
        return $this->handleResponse(TaskResource::collection($tasks), 'Tasks have been retrieved!');
    }

    
    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'title' => 'required',
            'file' => 'required|mimes:json'
        ]);
        if($validator->fails()){
            return $this->handleError($validator->errors());       
        }
        $objFile=$request->file;
        $input['file'] = $objFile->store(Config::get('constants.TASK_FILE_PATH'));
        $task = Task::create($input);
        return $this->handleResponse(new TaskResource($task), 'Task created!');
    }

   
    public function show($id)
    {
        $path=URL::to('/storage/app/');
        $task = Task::find($id);
        if (is_null($task)) {
            return $this->handleError('Task not found!');
        }
        $task->file=$path.'/'.$task->file;
        return $this->handleResponse(new TaskResource($task), 'Task retrieved.');
    }
    

    public function update(Request $request, Task $task)
    {
        $input = $request->all();
        //dd($input);
        $validator = Validator::make($input, [
            'title' => 'required',
            'file' => 'mimes:json'
        ]);

        if($validator->fails()){
            return $this->handleError($validator->errors());       
        }

        if ($request->file) 
        {
            if($task->file)
            {   
                unlink(storage_path('app/'.$task->file));
            }
            $objFile=$request->file;
            $task->file = $objFile->store(Config::get('constants.TASK_FILE_PATH'));
        }

        $task->title = $input['title'];
        $task->save();
        
        return $this->handleResponse(new TaskResource($task), 'Task successfully updated!');
    }
   
    public function destroy(Task $task)
    {   
       // dd($task);
        if (is_null($task)) {
            return $this->handleError('Task not found!');
        }
        if($task->file)
        {   
            unlink(storage_path('app/'.$task->file));
        }
        $task->delete();
        return $this->handleResponse([], 'Task deleted!');
    }

    public function updateTask(Request $request,$id)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'title' => 'required',
            'file' => 'mimes:json'
        ]);


        if($validator->fails()){
            return $this->handleError($validator->errors());       
        }
        $task = Task::find($id);
        if (is_null($task)) {
            return $this->handleError('Task not found!');
        }
        if ($request->file) 
        {
            if($task->file)
            {   
                unlink(storage_path('app/'.$task->file));
            }
            $objFile=$request->file;
            $task->file = $objFile->store(Config::get('constants.TASK_FILE_PATH'));
        }

        $task->title = $input['title'];
        $task->save();
        
        return $this->handleResponse(new TaskResource($task), 'Task successfully updated!');
    }

}