<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class File_Document extends Model
{
    protected $table = "file_documents";

    protected $primaryKey = "document_id";

    protected $fillable = ['file_ref', 'name', 'created_by', 'path', 'extension', 'size'];

    public function created_by()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
}
