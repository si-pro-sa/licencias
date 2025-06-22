<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Enums\FhirNoteType;
use App\Enums\FhirNoteStatus;

class FhirClinicalNote extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * La tabla asociada al modelo.
     *
     * @var string
     */
    protected $table = 'fhir_clinical_notes';

    /**
     * La clave primaria asociada a la tabla.
     *
     * @var string
     */
    protected $primaryKey = 'fhir_clinical_note_id';

    /**
     * Los atributos que son asignables en masa.
     *
     * @var array
     */
    protected $fillable = [
        'fhir_id',
        'fhir_patient_id',
        'fhir_encounter_id',
        'author_type',
        'author_id',
        'note_type',
        'content',
        'recorded_date',
        'status',
    ];

    /**
     * Los atributos que deben convertirse a tipos nativos.
     *
     * @var array
     */
    protected $casts = [
        'recorded_date' => 'datetime',
        'note_type' => FhirNoteType::class,
        'status' => FhirNoteStatus::class,
    ];

    /**
     * Obtiene el paciente asociado a esta nota.
     */
    public function patient()
    {
        return $this->belongsTo(FhirPatient::class, 'fhir_patient_id');
    }

    /**
     * Obtiene el encuentro asociado a esta nota.
     */
    public function encounter()
    {
        return $this->belongsTo(FhirEncounter::class, 'fhir_encounter_id');
    }

    /**
     * Obtiene el autor de la nota (polimórfico).
     */
    public function author()
    {
        if ($this->author_type === 'Provider') {
            return $this->belongsTo(FhirProvider::class, 'author_id');
        }

        // Otros tipos de autor pueden agregarse aquí
        return null;
    }

    /**
     * Obtener una versión resumida del contenido
     *
     * @param int $length
     * @return string
     */
    public function getSummaryAttribute($length = 100)
    {
        return strlen($this->content) > $length
            ? substr($this->content, 0, $length) . '...'
            : $this->content;
    }

    /**
     * Verifica si la nota es editable
     *
     * @return bool
     */
    public function getIsEditableAttribute()
    {
        return $this->status === FhirNoteStatus::PRELIMINARY ||
            ($this->status === FhirNoteStatus::FINAL &&
                $this->recorded_date->diffInHours(now()) < 24);
    }
}
