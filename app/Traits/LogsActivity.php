<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

trait LogsActivity
{
    protected static function bootLogsActivity()
    {
        static::created(function ($model) {
            $model->logActivity('created', 'Menambahkan data baru');
        });

        static::updated(function ($model) {
            $model->logActivity('updated', 'Memperbarui data');
        });

        static::deleted(function ($model) {
            $model->logActivity('deleted', 'Menghapus data');
        });
    }

    public function logActivity($action, $description)
    {
        if (!Auth::check()) {
            return;
        }

        // Title logic
        $title = 'Data #' . $this->id;
        if (method_exists($this, 'getActivityLogTitle')) {
            $title = $this->getActivityLogTitle();
        } elseif (isset($this->nama)) {
            $title = $this->nama;
        } elseif (isset($this->name)) {
            $title = $this->name;
        } elseif (isset($this->username)) {
            $title = $this->username;
        }

        $module = class_basename(static::class);
        if ($module == 'MasterKegiatan') $module = 'Master Kegiatan';
        elseif ($module == 'KegiatanAsatidz') $module = 'Kegiatan Asatidz';
        
        $changes = [];
        if ($action === 'updated') {
            $changes = [
                'old' => $this->getOriginal(),
                'new' => $this->getAttributes()
            ];
        } else {
            $changes = $this->toArray();
        }

        ActivityLog::create([
            'user_id' => Auth::id(),
            'module' => $module,
            'action' => $action,
            'subject_type' => static::class,
            'subject_id' => $this->id,
            'subject_title' => substr((string)$title, 0, 255),
            'description' => $description,
            'properties' => $changes,
        ]);
    }
}
