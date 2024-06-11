<?php

namespace Modules\Hr\App\Observers;

use Modules\Hr\App\Models\ActionLog;
use Modules\Hr\App\Models\BaseModel;
use Illuminate\Support\Arr;

class BaseModelObserver
{

    public function created(BaseModel $model)
    {
        $this->logAction($model, 'created');
    }

    public function updated(BaseModel $model)
    {
        $this->logAction($model, 'updated');
    }

    public function deleting(BaseModel $model)
    {
        $this->logAction($model, 'deleted');
    }

    public function restored(BaseModel $model)
    {
        $this->logAction($model, 'restored');
    }

    public function forceDeleted(BaseModel $model)
    {
        $this->logAction($model, 'forceDeleted');
    }

    protected function logAction(BaseModel $model, string $actionType)
    {
        $actionLog = new ActionLog();
        $actionLog->action = $actionType;
        $actionLog->model_type = get_class($model);
        $actionLog->model_id = $model->id;
        $actionLog->user_id = auth()->id();

        // For updates, store old and new data
        if ($actionType === 'updated') {
            $originalData = $model->getOriginal();
            $modifiedAttributes = $model->getDirty();
            $actionLog->old_data = json_encode(Arr::only($originalData, array_keys($modifiedAttributes)));
            $actionLog->new_data = json_encode($modifiedAttributes);
        }

        $actionLog->save();
    }


    // to add print to action log
    //to add file upload to action log
}
