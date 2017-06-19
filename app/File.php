<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $table = 'files';

    protected $primaryKey = 'file_id';

    protected $fillable = ['file_ref', 'category_id', 'sub_category_id', 'department_id', 'project_name', 'tags',
        'subject_matter', 'subject_description', 'contact_name', 'contact', 'contact_email', 'cases', 'introducer', 'mailing_address', 'residential_address',
        'billplz_collection_id' ,'currency', 'status', 'percent', 'outstanding_amount', 'paid_amount', 'created_by', 'updated_by', 'closed_by'];

    public function payments()
    {
        return $this->hasMany(Payment::class, 'file_ref', 'file_ref');
    }

    public function participants()
    {
        return $this->hasMany(File_User::class, 'file_ref', 'file_ref');
    }

}
