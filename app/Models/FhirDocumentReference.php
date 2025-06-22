<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Enums\FhirDocumentStatus;

class FhirDocumentReference extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'fhir_document_references';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'fhir_document_reference_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fhir_id',
        'status',
        'type',
        'category',
        'fhir_patient_id',
        'author_type',
        'author_id',
        'created',
        'content',
        'description',
        'path',
        'mimetype',
        'language'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'status' => FhirDocumentStatus::class,
        'type' => 'array',
        'category' => 'array',
        'content' => 'array',
        'created' => 'datetime'
    ];

    /**
     * Get the patient associated with the document.
     */
    public function patient()
    {
        return $this->belongsTo(FhirPatient::class, 'fhir_patient_id');
    }

    /**
     * Get the author of the document (polymorphic).
     */
    public function author()
    {
        if ($this->author_type === 'Provider') {
            return $this->belongsTo(FhirProvider::class, 'author_id');
        }

        // Other author types can be added here
        return null;
    }

    /**
     * Get document type display name.
     *
     * @return string|null
     */
    public function getTypeDisplayAttribute()
    {
        if (!$this->type || !isset($this->type['coding'][0]['display'])) {
            return null;
        }

        return $this->type['coding'][0]['display'];
    }

    /**
     * Get the URL of the document attachment.
     *
     * @return string|null
     */
    public function getAttachmentUrlAttribute()
    {
        if (empty($this->path)) {
            return null;
        }

        return url('storage/' . $this->path);
    }

    /**
     * Check if document is current.
     *
     * @return bool
     */
    public function getIsCurrentAttribute()
    {
        return $this->status === FhirDocumentStatus::CURRENT;
    }
}
